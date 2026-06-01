<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$isEdit = $product !== null;
$action = $isEdit ? site_url('admin/products/update/' . $product['id']) : site_url('admin/products/store');
?>

<div class="card">
    <form action="<?= $action ?>" method="post">
        <?= csrf_field() ?>

        <label>Nama Produk</label>
        <input type="text" name="name" class="form-control" value="<?= esc($product['name'] ?? '') ?>" required>

        <label>Jenis</label>
        <select name="type" class="form-control" required>
            <option value="">Pilih Jenis</option>
            <option value="Buah" <?= ($product['type'] ?? '') === 'Buah' ? 'selected' : '' ?>>Buah</option>
            <option value="Sayur" <?= ($product['type'] ?? '') === 'Sayur' ? 'selected' : '' ?>>Sayur</option>
        </select>

        <label>Satuan</label>
        <input type="text" name="unit" class="form-control" value="<?= esc($product['unit'] ?? 'kg') ?>" required>

        <label>Harga Jual (Rp)</label>
        <input type="number" name="price" class="form-control" value="<?= esc($product['price'] ?? 0) ?>" min="0" required>

        <label>Masa Segar / Shelf Life</label>
        <input type="number" name="shelf_life_days" class="form-control" value="<?= esc($product['shelf_life_days'] ?? 3) ?>" required>

        <label>Keterangan</label>
        <textarea name="description" class="form-control"><?= esc($product['description'] ?? '') ?></textarea>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= site_url('admin/products') ?>" class="btn btn-soft">Kembali</a>
    </form>
</div>

<?= $this->endSection() ?>