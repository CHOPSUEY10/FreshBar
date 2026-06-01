<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\StockBatchModel;
use App\Models\ScanLogModel;

class StockController extends BaseController
{
    private array $allowedStatuses = [
        'Segar',
        'Kurang Segar',
        'Tidak Segar',
    ];

    public function index()
    {
        helper('freshbar');

        $model = new StockBatchModel();
        
        $data = [
            'title' => 'Produk & Barang Masuk',
            'batches' => $model->getWithProducts(),
        ];

        // Jika admin, tampilkan juga master produk
        if (session()->get('role') === 'admin') {
            $productModel = new \App\Models\ProductModel();
            $data['products'] = $productModel->orderBy('id', 'DESC')->findAll();
        }

        return view('stock/index', $data);
    }

    public function create()
    {
        $productModel = new ProductModel();

        return view('stock/form', [
            'title' => 'Input Barang Masuk',
            'products' => $productModel->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $rules = [
            'product_name'    => 'required|max_length[100]',
            'type'            => 'required|max_length[50]',
            'unit'            => 'required|max_length[20]',
            'entry_date'      => 'required|valid_date',
            'quantity_in'     => 'required|integer|greater_than[0]',
            'price'           => 'required|integer|greater_than_equal_to[0]',
            'shelf_life_days' => 'required|integer|greater_than[0]',
            'location'        => 'permit_empty|max_length[100]',
            'note'            => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $stockModel = new StockBatchModel();
        $productModel = new ProductModel();

        $productName = trim($this->request->getPost('product_name'));
        
        // Cari produk berdasarkan nama
        $existingProduct = $productModel->where('name', $productName)->first();
        
        $productData = [
            'type'            => $this->request->getPost('type'),
            'unit'            => $this->request->getPost('unit'),
            'price'           => $this->request->getPost('price'),
            'shelf_life_days' => $this->request->getPost('shelf_life_days'),
        ];
        
        if ($existingProduct) {
            $productId = $existingProduct['id'];
            // Jika produk belum punya barcode (migrasi), buatkan
            if (empty($existingProduct['barcode'])) {
                $productData['barcode'] = 'FRB-' . date('YmdHis') . '-' . rand(100, 999);
            }
            // Update data master produk yang sudah ada
            $productModel->update($productId, $productData);
        } else {
            // Insert produk baru
            $productData['name'] = $productName;
            $productData['barcode'] = 'FRB-' . date('YmdHis') . '-' . rand(100, 999);
            $productId = $productModel->insert($productData);
        }

        $qty = (int) $this->request->getPost('quantity_in');

        $stockModel->insert([
            'product_id'        => $productId,
            'entry_date'        => $this->request->getPost('entry_date'),
            'quantity_in'       => $qty,
            'quantity_current'  => $qty,
            'freshness_status'  => 'Segar',
            'location'          => $this->request->getPost('location'),
            'note'              => $this->request->getPost('note'),
            'created_by'        => session()->get('user_id'),
        ]);

        return redirect()
            ->to(site_url('stock'))
            ->with('success', 'Barang masuk berhasil disimpan dan barcode berhasil dibuat.');
    }

    public function editStock($id)
    {
        $model = new StockBatchModel();

        $batch = $model->getWithProducts($id);

        if (!$batch) {
            return redirect()
                ->to(site_url('stock'))
                ->with('error', 'Data barang masuk tidak ditemukan.');
        }

        return view('stock/update', [
            'title'    => 'Update Barang Masuk',
            'batch'    => $batch,
            'statuses' => $this->allowedStatuses,
        ]);
    }

    public function saveStock($id)
    {
        $model = new StockBatchModel();

        $batch = $model->find($id);

        if (!$batch) {
            return redirect()
                ->to(site_url('stock'))
                ->with('error', 'Data barang masuk tidak ditemukan.');
        }

        $quantityCurrent = (int) $this->request->getPost('quantity_current');
        $freshnessStatus = $this->request->getPost('freshness_status');

        if ($quantityCurrent < 0) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Stok saat ini tidak boleh kurang dari 0.');
        }

        if (!in_array($freshnessStatus, $this->allowedStatuses, true)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Status kesegaran tidak valid.');
        }

        $model->update($id, [
            'quantity_current' => $quantityCurrent,
            'freshness_status' => $freshnessStatus,
            'note'             => $this->request->getPost('note'),
        ]);

        return redirect()
            ->to(site_url('stock'))
            ->with('success', 'Stok dan status kesegaran berhasil diperbarui.');
    }

    public function delete($id)
    {
        $stockModel = new StockBatchModel();
        $scanLogModel = new ScanLogModel();

        $batch = $stockModel->find($id);

        if (!$batch) {
            return redirect()
                ->to(site_url('stock'))
                ->with('error', 'Data barang masuk tidak ditemukan.');
        }

        /*
         * Scan log dihapus dulu supaya tidak bentrok kalau tabel scan_logs
         * punya relasi ke stock_batches.
         */
        $scanLogModel
            ->where('batch_id', $id)
            ->delete();

        $stockModel->delete($id);

        return redirect()
            ->to(site_url('stock'))
            ->with('success', 'Data barang masuk berhasil dihapus.');
    }
}