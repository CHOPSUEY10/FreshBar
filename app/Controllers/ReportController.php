<?php

namespace App\Controllers;

use App\Models\StockBatchModel;
use App\Models\ScanLogModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController extends BaseController
{
    public function index()
    {
        $data = $this->buildReportData();

        return view('reports/index', $data);
    }

    public function exportPdf()
    {
        if (! class_exists(Dompdf::class)) {
            return redirect()
                ->to(site_url('admin/reports'))
                ->with('error', 'Export PDF gagal. Dompdf belum terinstall. Jalankan: composer require dompdf/dompdf');
        }

        try {
            $data = $this->buildReportData();
            $data['printedAt'] = date('d-m-Y H:i:s');

            $html = view('reports/pdf', $data);

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('chroot', ROOTPATH);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            $fileName = 'laporan-freshbar-' . date('Ymd-His') . '.pdf';

            return $this->response
                ->setStatusCode(200)
                ->setContentType('application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->setBody($dompdf->output());
        } catch (\Throwable $e) {
            log_message('error', 'Export PDF gagal: ' . $e->getMessage());

            return redirect()
                ->to(site_url('admin/reports'))
                ->with('error', 'Export PDF gagal: ' . $e->getMessage());
        }
    }

    private function buildReportData()
    {
        helper('freshbar');

        $stockModel = new StockBatchModel();
        $scanModel = new ScanLogModel();
        
        $db = \Config\Database::connect();
        
        // Fetch ALL products
        $products = $db->query("SELECT * FROM products ORDER BY name ASC")->getResultArray();
        
        // Fetch ALL batches
        $batches = $stockModel->getWithProducts();
        
        // Group batches by product_id
        $batchesByProduct = [];
        foreach ($batches as $batch) {
            $batchesByProduct[$batch['product_id']][] = $batch;
        }

        $totalSegar = 0;
        $totalKurangSegar = 0;
        $totalTidakSegar = 0;

        $reportRows = [];

        foreach ($products as $product) {
            if (isset($batchesByProduct[$product['id']])) {
                // Product has batches, add a row for each batch
                foreach ($batchesByProduct[$product['id']] as $batch) {
                    $autoFreshness = freshness_status($batch['entry_date'], $batch['shelf_life_days']);

                    if (!empty($batch['freshness_status'])) {
                        $freshness = freshness_status_detail($batch['freshness_status']);
                        $freshness['age'] = $autoFreshness['age'];
                        $freshness['percentage'] = $autoFreshness['percentage'];
                    } else {
                        $freshness = $autoFreshness;
                    }

                    if ($freshness['status'] === 'Segar') {
                        $totalSegar++;
                    } elseif ($freshness['status'] === 'Tidak Segar') {
                        $totalTidakSegar++;
                    } else {
                        $totalKurangSegar++;
                    }

                    $batch['freshness'] = $freshness;
                    $reportRows[] = $batch;
                }
            } else {
                // Product has no batches (Out of Stock)
                $reportRows[] = [
                    'barcode' => '-',
                    'product_name' => $product['name'],
                    'type' => $product['type'],
                    'unit' => $product['unit'],
                    'entry_date' => '-',
                    'quantity_current' => 0,
                    'freshness' => [
                        'status' => 'Kosong',
                        'age' => '-',
                        'recommendation' => 'Stok habis. Segera lakukan pengadaan barang (input barang masuk).'
                    ]
                ];
            }
        }

        return [
            'title'             => 'Laporan Freshbar',
            'batches'           => $reportRows,
            'totalBatch'        => count($batches), // Original batch count
            'totalScan'         => $scanModel->countAllResults(),
            'totalSegar'        => $totalSegar,
            'totalKurangSegar'  => $totalKurangSegar,
            'totalTidakSegar'   => $totalTidakSegar,
        ];
    }
}