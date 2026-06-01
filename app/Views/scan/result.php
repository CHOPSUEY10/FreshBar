<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <h2>Hasil Deteksi Kesegaran</h2>

    <table class="table">
        <tr>
            <th>Barcode</th>
            <td><?= esc($batch['barcode']) ?></td>
        </tr>
        <tr>
            <th>Produk</th>
            <td><?= esc($batch['product_name']) ?></td>
        </tr>
        <tr>
            <th>Jenis</th>
            <td><?= esc($batch['type']) ?></td>
        </tr>
        <tr>
            <th>Tanggal Masuk</th>
            <td><?= esc($batch['entry_date']) ?></td>
        </tr>
        <tr>
            <th>Umur Penyimpanan</th>
            <td><?= esc($freshness['age']) ?> hari</td>
        </tr>
        <tr>
            <th>Masa Segar</th>
            <td><?= esc($batch['shelf_life_days']) ?> hari</td>
        </tr>
        <tr>
            <th>Stok Saat Ini</th>
            <td><?= esc($batch['quantity_current']) ?> <?= esc($batch['unit']) ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <span class="badge <?= esc($freshness['badge']) ?>">
                    <?= esc($freshness['status']) ?>
                </span>
            </td>
        </tr>
        <tr>
            <th>Rekomendasi</th>
            <td><?= esc($freshness['recommendation']) ?></td>
        </tr>
    </table>

    <br>

    <a href="<?= site_url('scan') ?>" class="btn btn-primary">Scan Lagi</a>
    <a href="<?= site_url('stock/update/' . $batch['id']) ?>" class="btn btn-soft">Update Stok</a>
</div>

<?= $this->endSection() ?>