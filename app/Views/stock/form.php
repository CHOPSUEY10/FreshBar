<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <form action="<?= site_url('stock/store') ?>" method="post">
        <?= csrf_field() ?>

        <label>Produk</label>
        <select name="product_id" class="form-control" required>
            <option value="">Pilih Produk</option>
            <?php foreach ($products as $product): ?>
                <option value="<?= esc($product['id']) ?>">
                    <?= esc($product['name']) ?> - <?= esc($product['type']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Tanggal Masuk</label>
        <input type="date" name="entry_date" class="form-control" value="<?= date('Y-m-d') ?>" required>

        <label>Jumlah Masuk</label>
        <input type="number" name="quantity_in" class="form-control" min="1" required>

        <label>Lokasi Penyimpanan</label>
        <input type="text" name="location" class="form-control" placeholder="Contoh: Rak A1 / Cold Storage">

        <label>Catatan</label>
        <textarea name="note" class="form-control"></textarea>

        <button type="submit" class="btn btn-primary">Simpan & Generate Barcode</button>
        <a href="<?= site_url('stock') ?>" class="btn btn-soft">Kembali</a>
    </form>
</div>

<?= $this->endSection() ?>