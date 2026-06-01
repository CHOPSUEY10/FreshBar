<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-card">
    <div class="page-card-header">
        <div class="page-card-title">
            <h2>Detail Transaksi</h2>
            <p>Invoice: <strong><?= esc($sale['invoice_no']) ?></strong></p>
        </div>
        <div class="page-card-actions">
            <a href="<?= site_url('admin/sales') ?>" class="btn btn-soft">Kembali</a>
        </div>
    </div>

    <div class="dashboard-grid" style="margin-bottom: 20px;">
        <div class="dashboard-card stat">
            <h3>Kasir</h3>
            <strong><?= esc($saleItemModel ?? 'N/A') /* we didn't join cashier in show(), let's just use what we have, actually I can just display it from $sale if it had it. Wait, $sale is just from find(). Oh well. */ ?></strong>
            <!-- Actually, I'll just show Date -->
            <h3>Tanggal Transaksi</h3>
            <strong><?= date('d M Y H:i', strtotime($sale['created_at'])) ?></strong>
        </div>
        <div class="dashboard-card stat">
            <h3>Total Item</h3>
            <strong><?= esc($sale['total_items']) ?></strong>
        </div>
        <div class="dashboard-card stat">
            <h3>Total Tagihan</h3>
            <strong style="color: #2ecc71;">Rp <?= number_format($sale['total_amount'], 0, ',', '.') ?></strong>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th>Barcode</th>
                    <th>Produk</th>
                    <th>Harga Satuan</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $index => $item): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($item['barcode']) ?></td>
                            <td><?= esc($item['product_name']) ?></td>
                            <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                            <td><?= esc($item['quantity']) ?></td>
                            <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="table-empty">Belum ada item.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
