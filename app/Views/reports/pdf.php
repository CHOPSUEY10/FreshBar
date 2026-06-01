<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Freshbar</title>

    <style>
        @page {
            margin: 24px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #111827;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .header {
            border-bottom: 2px solid #0f9f95;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0 0 6px;
            font-size: 22px;
            color: #111827;
        }

        .header p {
            margin: 0 0 4px;
            color: #6b7280;
            font-size: 11px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .summary td {
            width: 25%;
            border: 1px solid #e5e7eb;
            padding: 12px;
            vertical-align: top;
        }

        .summary-title {
            color: #6b7280;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .summary-number {
            color: #111827;
            font-size: 24px;
            font-weight: bold;
            line-height: 1;
        }

        .summary-desc {
            color: #6b7280;
            font-size: 10px;
            margin-top: 8px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #e5e7eb;
            padding: 7px 6px;
            text-align: left;
            vertical-align: top;
            font-size: 9px;
            word-wrap: break-word;
        }

        .report-table th {
            background: #e9f8f6;
            color: #08746d;
            font-weight: bold;
        }

        .col-no {
            width: 5%;
            text-align: center;
        }

        .col-barcode {
            width: 17%;
        }

        .col-produk {
            width: 11%;
        }

        .col-jenis {
            width: 9%;
        }

        .col-tanggal {
            width: 11%;
        }

        .col-stok {
            width: 9%;
        }

        .col-umur {
            width: 8%;
        }

        .col-status {
            width: 12%;
        }

        .col-rekomendasi {
            width: 18%;
        }

        .status {
            display: inline-block;
            padding: 4px 7px;
            border-radius: 12px;
            font-weight: bold;
            text-align: center;
            min-width: 70px;
            font-size: 8px;
        }

        .status-segar {
            background: #dcfce7;
            color: #166534;
        }

        .status-kurang {
            background: #fef3c7;
            color: #92400e;
        }

        .status-tidak {
            background: #fee2e2;
            color: #991b1b;
        }

        .footer {
            margin-top: 16px;
            color: #6b7280;
            font-size: 10px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Kesegaran Produk Freshbar</h1>
        <p>Ringkasan laporan stok dan status kesegaran buah serta sayur.</p>
        <p>Tanggal cetak: <?= esc($printedAt ?? date('d-m-Y H:i:s')) ?></p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-title">Total Batch</div>
                <div class="summary-number"><?= esc($totalBatch ?? 0) ?></div>
                <div class="summary-desc">Semua data barang masuk</div>
            </td>

            <td>
                <div class="summary-title">Jumlah Segar</div>
                <div class="summary-number"><?= esc($totalSegar ?? 0) ?></div>
                <div class="summary-desc">Produk masih aman dijual</div>
            </td>

            <td>
                <div class="summary-title">Jumlah Kurang Segar</div>
                <div class="summary-number"><?= esc($totalKurangSegar ?? 0) ?></div>
                <div class="summary-desc">Produk perlu diprioritaskan</div>
            </td>

            <td>
                <div class="summary-title">Jumlah Tidak Segar</div>
                <div class="summary-number"><?= esc($totalTidakSegar ?? 0) ?></div>
                <div class="summary-desc">Produk perlu dipisahkan</div>
            </td>
        </tr>
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-barcode">Barcode</th>
                <th class="col-produk">Produk</th>
                <th class="col-jenis">Jenis</th>
                <th class="col-tanggal">Tanggal Masuk</th>
                <th class="col-stok">Stok</th>
                <th class="col-umur">Umur</th>
                <th class="col-status">Status</th>
                <th class="col-rekomendasi">Rekomendasi</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($batches)): ?>
                <?php foreach ($batches as $index => $batch): ?>
                    <?php
                        $fresh = $batch['freshness'];

                        if ($fresh['status'] === 'Segar') {
                            $statusClass = 'status-segar';
                            $statusText = 'Segar';
                        } elseif ($fresh['status'] === 'Tidak Segar') {
                            $statusClass = 'status-tidak';
                            $statusText = 'Tidak Segar';
                        } else {
                            $statusClass = 'status-kurang';
                            $statusText = 'Kurang Segar';
                        }
                    ?>

                    <tr>
                        <td class="col-no"><?= $index + 1 ?></td>
                        <td><?= esc($batch['barcode']) ?></td>
                        <td><?= esc($batch['product_name']) ?></td>
                        <td><?= esc($batch['type']) ?></td>
                        <td><?= esc($batch['entry_date']) ?></td>
                        <td><?= esc($batch['quantity_current']) ?> <?= esc($batch['unit']) ?></td>
                        <td><?= esc($fresh['age']) ?> hari</td>
                        <td>
                            <span class="status <?= esc($statusClass) ?>">
                                <?= esc($statusText) ?>
                            </span>
                        </td>
                        <td><?= esc($fresh['recommendation']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 14px;">
                        Belum ada data laporan.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Freshbar - Freshness Stock System
    </div>
</body>
</html>