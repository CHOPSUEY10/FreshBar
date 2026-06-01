<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    .report-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
        margin-bottom: 28px;
    }

    .report-stat {
        background: #ffffff;
        border-radius: 18px;
        padding: 24px;
        min-height: 130px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.045);
        border: 1px solid #f1f5f9;
    }

    .report-stat h3 {
        margin: 0 0 16px;
        font-size: 15px;
        color: #6b7280;
        font-weight: 800;
    }

    .report-stat strong {
        display: block;
        font-size: 34px;
        color: #111827;
        line-height: 1;
    }

    .report-stat span {
        display: block;
        margin-top: 10px;
        font-size: 13px;
        color: #6b7280;
    }

    .report-table-wrap {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 12px;
    }

    .report-table-wrap::-webkit-scrollbar {
        height: 10px;
    }

    .report-table-wrap::-webkit-scrollbar-track {
        background: #eef5f4;
        border-radius: 999px;
    }

    .report-table-wrap::-webkit-scrollbar-thumb {
        background: #b7deda;
        border-radius: 999px;
    }

    .report-table-wrap::-webkit-scrollbar-thumb:hover {
        background: #0f9f95;
    }

    .report-table {
        width: 1450px;
        min-width: 1450px;
        max-width: none;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .report-table th,
    .report-table td {
        padding: 17px 16px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        vertical-align: middle;
        font-size: 15px;
        color: #111827;
        white-space: nowrap;
    }

    .report-table th {
        color: #08746d;
        font-weight: 800;
        background: #ffffff;
    }

    .report-table tbody tr:hover {
        background: #fbfefe;
    }

    .report-table .col-no {
        width: 70px;
        text-align: center;
    }

    .report-table .col-barcode {
        width: 250px;
    }

    .report-table .col-produk {
        width: 160px;
    }

    .report-table .col-jenis {
        width: 120px;
    }

    .report-table .col-tanggal {
        width: 170px;
    }

    .report-table .col-stok {
        width: 130px;
    }

    .report-table .col-umur {
        width: 130px;
    }

    .report-table .col-status {
        width: 180px;
    }

    .report-table .col-rekomendasi {
        width: 440px;
    }

    .barcode-text {
        white-space: normal;
        word-break: break-word;
        line-height: 1.45;
    }

    .recommendation-text {
        white-space: normal;
        line-height: 1.55;
        color: #1f2937;
    }

    .status-badge {
        padding: 9px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        display: inline-block;
        text-align: center;
        min-width: 120px;
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

    .status-kosong {
        background: #f3f4f6;
        color: #374151;
    }

    .table-empty {
        text-align: center !important;
        color: #6b7280 !important;
        padding: 28px 16px !important;
    }

    .report-export-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    @media (max-width: 1100px) {
        .report-summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .report-summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-card">
    <div class="page-card-header">
        <div class="page-card-title">
            <h2>Laporan Kesegaran Produk</h2>
            <p>Ringkasan laporan stok dan status kesegaran buah serta sayur.</p>
        </div>

        <div class="page-card-actions report-export-actions">
            <a href="<?= site_url('admin/reports/export-pdf') ?>" class="btn btn-primary">
                Export PDF
            </a>
        </div>
    </div>

    <div class="report-summary-grid">
        <div class="report-stat">
            <h3>Total Batch</h3>
            <strong><?= esc($totalBatch ?? 0) ?></strong>
            <span>Semua data barang masuk</span>
        </div>

        <div class="report-stat">
            <h3>Jumlah Segar</h3>
            <strong><?= esc($totalSegar ?? 0) ?></strong>
            <span>Produk masih aman dijual</span>
        </div>

        <div class="report-stat">
            <h3>Jumlah Kurang Segar</h3>
            <strong><?= esc($totalKurangSegar ?? 0) ?></strong>
            <span>Produk perlu diprioritaskan</span>
        </div>

        <div class="report-stat">
            <h3>Jumlah Tidak Segar</h3>
            <strong><?= esc($totalTidakSegar ?? 0) ?></strong>
            <span>Produk perlu dipisahkan</span>
        </div>
    </div>

    <div class="report-table-wrap">
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
                            } elseif ($fresh['status'] === 'Kosong') {
                                $statusClass = 'status-kosong';
                                $statusText = 'Kosong';
                            } else {
                                $statusClass = 'status-kurang';
                                $statusText = 'Kurang Segar';
                            }
                        ?>

                        <tr>
                            <td class="col-no"><?= $index + 1 ?></td>

                            <td>
                                <div class="barcode-text">
                                    <?= esc($batch['barcode']) ?>
                                </div>
                            </td>

                            <td><?= esc($batch['product_name']) ?></td>

                            <td><?= esc($batch['type']) ?></td>

                            <td><?= esc($batch['entry_date']) ?></td>

                            <td>
                                <?= esc($batch['quantity_current']) ?>
                                <?= esc($batch['unit']) ?>
                            </td>

                            <td><?= esc($fresh['age']) ?> hari</td>

                            <td>
                                <span class="status-badge <?= esc($statusClass) ?>">
                                    <?= esc($statusText) ?>
                                </span>
                            </td>

                            <td>
                                <div class="recommendation-text">
                                    <?= esc($fresh['recommendation']) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="table-empty">
                            Belum ada data laporan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>