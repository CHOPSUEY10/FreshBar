<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\StockBatchModel;
use CodeIgniter\API\ResponseTrait;

class SalesController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to(site_url('dashboard'))->with('error', 'Akses ditolak. Hanya admin yang dapat melihat laporan penjualan.');
        }

        $saleModel = new SaleModel();
        
        return view('sales/index', [
            'title' => 'Laporan Penjualan',
            'sales' => $saleModel->getSalesWithUser(),
        ]);
    }

    public function show($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to(site_url('dashboard'))->with('error', 'Akses ditolak.');
        }

        $saleModel = new SaleModel();
        $saleItemModel = new SaleItemModel();

        $sale = $saleModel->find($id);
        if (!$sale) {
            return redirect()->to(site_url('admin/sales'))->with('error', 'Transaksi tidak ditemukan.');
        }

        return view('sales/show', [
            'title' => 'Detail Penjualan',
            'sale' => $sale,
            'items' => $saleItemModel->getItemsBySaleId($id),
        ]);
    }

    public function create()
    {
        return view('sales/create', [
            'title' => 'Pencatatan Penjualan (POS)',
        ]);
    }

    public function scanBarcode()
    {
        $barcode = $this->request->getPost('barcode');
        if (!$barcode) {
            return $this->respond(['status' => 'error', 'message' => 'Barcode tidak boleh kosong.', 'csrf' => csrf_hash()], 400);
        }

        $stockModel = new StockBatchModel();
        // Cari batch stok yang paling tua (FIFO) untuk produk dengan barcode ini
        $batch = $stockModel->getOldestAvailableBatchByBarcode($barcode);

        if (!$batch) {
            return $this->respond(['status' => 'error', 'message' => 'Stok produk habis atau barcode tidak ditemukan.', 'csrf' => csrf_hash()], 404);
        }

        if ((int)$batch['quantity_current'] <= 0) {
            return $this->respond(['status' => 'error', 'message' => 'Stok barang habis (0).', 'csrf' => csrf_hash()], 400);
        }

        helper('freshbar');
        $autoFreshness = freshness_status($batch['entry_date'], $batch['shelf_life_days']);
        $manualStatus = $batch['freshness_status'] ?? $autoFreshness['status'];

        if ($manualStatus === 'Tidak Segar') {
            return $this->respond(['status' => 'error', 'message' => 'Barang tidak dapat dijual karena berstatus Tidak Segar.', 'csrf' => csrf_hash()], 400);
        }

        // Return batch and product details
        return $this->respond([
            'status' => 'success',
            'csrf' => csrf_hash(),
            'data' => [
                'batch_id' => $batch['id'],
                'barcode' => $batch['barcode'],
                'product_name' => $batch['product_name'],
                'price' => (int)$batch['price'],
                'quantity_current' => (int)$batch['quantity_current'],
                'unit' => $batch['unit']
            ]
        ]);
    }

    public function store()
    {
        $cart = $this->request->getPost('cart');
        if (empty($cart) || !is_array($cart)) {
            return $this->respond(['status' => 'error', 'message' => 'Keranjang belanja kosong.', 'csrf' => csrf_hash()], 400);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $saleModel = new SaleModel();
        $saleItemModel = new SaleItemModel();
        $stockModel = new StockBatchModel();

        $invoiceNo = 'INV-' . date('YmdHis') . '-' . rand(100, 999);
        $totalItems = 0;
        $totalAmount = 0;

        $saleId = $saleModel->insert([
            'invoice_no' => $invoiceNo,
            'user_id' => session()->get('user_id'),
            'total_items' => 0,
            'total_amount' => 0,
        ], true); // return ID

        $productModel = new \App\Models\ProductModel();

        foreach ($cart as $item) {
            $qtyToDeduct = (int) $item['quantity'];
            $barcode = $item['barcode'];
            $price = (int) $item['price'];
            
            // Cari product_id berdasarkan barcode
            $product = $productModel->where('barcode', $barcode)->first();
            if (!$product) {
                $db->transRollback();
                return $this->respond(['status' => 'error', 'message' => 'Produk dengan barcode ' . $barcode . ' tidak ditemukan.', 'csrf' => csrf_hash()], 400);
            }

            // Cari semua batch stok untuk produk ini yang masih ada isinya, urutkan dari yang paling tua (FIFO)
            $availableBatches = $stockModel->where('product_id', $product['id'])
                                           ->where('quantity_current >', 0)
                                           ->orderBy('entry_date', 'ASC')
                                           ->findAll();

            $totalAvailableStock = array_reduce($availableBatches, function($carry, $b) {
                return $carry + $b['quantity_current'];
            }, 0);

            if ($totalAvailableStock < $qtyToDeduct) {
                $db->transRollback();
                return $this->respond(['status' => 'error', 'message' => 'Total stok tidak cukup untuk produk: ' . $product['name'], 'csrf' => csrf_hash()], 400);
            }

            foreach ($availableBatches as $batch) {
                if ($qtyToDeduct <= 0) break;

                $batchId = $batch['id'];
                $availableInBatch = (int) $batch['quantity_current'];
                
                // Tentukan berapa yang diambil dari batch ini
                $takeFromBatch = min($availableInBatch, $qtyToDeduct);
                
                // Deduct stock dari batch
                $stockModel->update($batchId, [
                    'quantity_current' => $availableInBatch - $takeFromBatch
                ]);

                $subtotal = $price * $takeFromBatch;
                $totalItems += $takeFromBatch;
                $totalAmount += $subtotal;

                // Catat di sale_items per batch
                $saleItemModel->insert([
                    'sale_id' => $saleId,
                    'batch_id' => $batchId,
                    'quantity' => $takeFromBatch,
                    'price' => $price,
                    'subtotal' => $subtotal
                ]);

                // Kurangi sisa yang masih harus dipotong
                $qtyToDeduct -= $takeFromBatch;
            }
        }

        $saleModel->update($saleId, [
            'total_items' => $totalItems,
            'total_amount' => $totalAmount
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->respond(['status' => 'error', 'message' => 'Gagal menyimpan transaksi.', 'csrf' => csrf_hash()], 500);
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'Transaksi berhasil disimpan dengan Invoice: ' . $invoiceNo,
            'invoice_no' => $invoiceNo,
            'csrf' => csrf_hash()
        ]);
    }
}
