<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\UserModel;
use App\Models\StockBatchModel;

class DashboardController extends BaseController
{
    public function index()
    {
        helper('freshbar');

        $productModel = new ProductModel();
        $userModel = new UserModel();
        $stockModel = new StockBatchModel();

        $batches = $stockModel->getWithProducts();

        $fresh = 0;
        $warning = 0;
        $bad = 0;

        foreach ($batches as $batch) {
            $status = \freshness_status($batch['entry_date'], $batch['shelf_life_days']);

            if ($status['status'] === 'Segar') {
                $fresh++;
            } elseif ($status['status'] === 'Tidak Segar') {
                $bad++;
            } else {
                $warning++;
            }
        }

        return view('dashboard/index', [
            'title' => 'Dashboard',
            'totalProducts' => $productModel->countAllResults(),
            'totalStaff' => $userModel->where('role', 'staff')->countAllResults(),
            'totalBatches' => count($batches),
            'fresh' => $fresh,
            'warning' => $warning,
            'bad' => $bad,
        ]);
    }
}