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
}