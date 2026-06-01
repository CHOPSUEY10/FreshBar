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

        $db = \Config\Database::connect();
        $year = date('Y');

        // Chart Data: Stock Masuk Bulanan
        $queryIn = $db->query("
            SELECT MONTH(entry_date) as month, SUM(quantity_in) as total_in
            FROM stock_batches
            WHERE YEAR(entry_date) = ?
            GROUP BY MONTH(entry_date)
        ", [$year])->getResultArray();

        // Chart Data: Stock Keluar (Tidak Fresh) Bulanan
        $queryOut = $db->query("
            SELECT MONTH(entry_date) as month, SUM(quantity_in - quantity_current) as total_out_bad
            FROM stock_batches
            WHERE YEAR(entry_date) = ? AND freshness_status = 'Tidak Segar'
            GROUP BY MONTH(entry_date)
        ", [$year])->getResultArray();

        // Siapkan array data untuk 12 bulan (Jan-Des)
        $monthlyIn = array_fill(1, 12, 0);
        $monthlyOutBad = array_fill(1, 12, 0);
        $monthlySales = array_fill(1, 12, 0);

        foreach ($queryIn as $row) {
            $monthlyIn[(int)$row['month']] = (int)$row['total_in'];
        }

        foreach ($queryOut as $row) {
            $monthlyOutBad[(int)$row['month']] = (int)$row['total_out_bad'];
        }

        // Chart Data: Penjualan Bulanan (Total Amount)
        $querySales = $db->query("
            SELECT MONTH(created_at) as month, SUM(total_amount) as total_sales
            FROM sales
            WHERE YEAR(created_at) = ?
            GROUP BY MONTH(created_at)
        ", [$year])->getResultArray();

        foreach ($querySales as $row) {
            $monthlySales[(int)$row['month']] = (int)$row['total_sales'];
        }

        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'stockIn' => array_values($monthlyIn),
            'stockOutBad' => array_values($monthlyOutBad),
            'salesTotal' => array_values($monthlySales),
        ];

        return view('dashboard/index', [
            'title' => 'Dashboard',
            'totalProducts' => $productModel->countAllResults(),
            'totalStaff' => $userModel->where('role', 'staff')->countAllResults(),
            'totalBatches' => count($batches),
            'fresh' => $fresh,
            'warning' => $warning,
            'bad' => $bad,
            'chartData' => json_encode($chartData),
        ]);
    }
}