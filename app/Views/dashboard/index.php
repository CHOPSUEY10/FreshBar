<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
    $role = session()->get('role');

    $totalProducts = (int) ($totalProducts ?? 0);
    $totalBatches = (int) ($totalBatches ?? 0);
    $fresh = (int) ($fresh ?? 0);
    $warning = (int) ($warning ?? 0);
    $bad = (int) ($bad ?? 0);
    $needCheck = $warning + $bad;
?>

<div class="dashboard-grid">
    <div class="stat">
        <h3>Total Produk</h3>
        <strong><?= esc($totalProducts) ?></strong>
    </div>

    <div class="stat">
        <h3>Batch Barang</h3>
        <strong><?= esc($totalBatches) ?></strong>
    </div>

    <div class="stat">
        <h3>Produk Segar</h3>
        <strong><?= esc($fresh) ?></strong>
    </div>

    <div class="stat">
        <h3>Perlu Dicek</h3>
        <strong><?= esc($needCheck) ?></strong>
    </div>
</div>

<div class="dashboard-card welcome-box">
    <h2>Selamat datang di Freshbar 🍃</h2>

    <p>
        Sistem ini digunakan untuk mengelola stok buah dan sayur, membuat barcode,
        scan barcode, serta mendeteksi kesegaran otomatis berdasarkan tanggal masuk.
    </p>

    <?php if ($role === 'admin'): ?>
        <p>
            Role kamu adalah <b>Admin</b>. Kamu dapat mengelola produk, staff,
            barang masuk, barcode, scan, dan laporan.
        </p>
    <?php else: ?>
        <p>
            Role kamu adalah <b>Staff Gudang</b>. Kamu dapat input barang masuk,
            update stok, dan scan barcode.
        </p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>