<?php

namespace App\Controllers;

use App\Models\StockBatchModel;
use App\Models\ScanLogModel;

class ScanController extends BaseController
{
    public function index()
    {
        return view('scan/index', [
            'title' => 'Scan Barcode',
        ]);
    }

    public function check()
    {
        helper('freshbar');

        $barcode = trim((string) $this->request->getPost('barcode'));

        $stockModel = new StockBatchModel();
        $scanModel = new ScanLogModel();

        $batch = $stockModel->findByBarcode($barcode);

        if (!$batch) {
            return redirect()
                ->back()
                ->with('error', 'Barcode tidak ditemukan.');
        }

        $autoFreshness = freshness_status($batch['entry_date'], $batch['shelf_life_days']);
        $manualStatus = $batch['freshness_status'] ?? $autoFreshness['status'];

        $freshness = freshness_status_detail($manualStatus);
        $freshness['age'] = $autoFreshness['age'];
        $freshness['percentage'] = $autoFreshness['percentage'];

        $scanModel->insert([
            'batch_id' => $batch['id'],
            'user_id' => session()->get('user_id'),
            'freshness_status' => $freshness['status'],
            'scanned_at' => date('Y-m-d H:i:s'),
        ]);

        return view('scan/result', [
            'title' => 'Hasil Scan Barcode',
            'batch' => $batch,
            'freshness' => $freshness,
        ]);
    }
}