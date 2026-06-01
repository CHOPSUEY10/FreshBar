<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-card">
    <div class="page-card-header">
        <div class="page-card-title">
            <h2>Laporan Penjualan</h2>
            <p>Riwayat transaksi penjualan (Admin Only).</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th>No. Invoice</th>
                    <th>Kasir</th>
                    <th>Tanggal</th>
                    <th>Total Item</th>
                    <th>Total Nominal</th>
                    <th class="col-action">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sales)): ?>
                    <?php foreach ($sales as $index => $sale): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><strong><?= esc($sale['invoice_no']) ?></strong></td>
                            <td><?= esc($sale['cashier_name']) ?></td>
                            <td><?= date('d M Y H:i', strtotime($sale['created_at'])) ?></td>
                            <td><?= esc($sale['total_items']) ?></td>
                            <td style="color: #2ecc71; font-weight: bold;">Rp <?= number_format($sale['total_amount'], 0, ',', '.') ?></td>
                            <td>
                                <a class="btn btn-soft btn-sm" href="<?= site_url('admin/sales/show/' . $sale['id']) ?>">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="table-empty">
                            Belum ada transaksi penjualan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
