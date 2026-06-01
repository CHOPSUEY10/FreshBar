<?php

namespace App\Controllers;

use App\Models\StockBatchModel;

class BarcodeController extends BaseController
{
    public function print($id)
    {
        $model = new StockBatchModel();

        return view('barcode/print', [
            'title' => 'Cetak Barcode',
            'batch' => $model->getWithProducts($id),
        ]);
    }

    public function print_product($id)
    {
        $model = new \App\Models\ProductModel();
        $product = $model->find($id);

        if (!$product || empty($product['barcode'])) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan atau belum memiliki barcode.');
        }

        // Kita gunakan view yang sama, tapi format datanya agar kompatibel dengan view print
        $fakeBatch = [
            'barcode' => $product['barcode'],
            'product_name' => $product['name'],
            'price' => $product['price']
        ];

        return view('barcode/print', [
            'title' => 'Cetak Barcode',
            'batch' => $fakeBatch,
        ]);
    }
}