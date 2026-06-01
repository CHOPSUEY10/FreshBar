<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <h2><?= esc($batch['product_name']) ?></h2>

    <p>Barcode: <b><?= esc($batch['barcode']) ?></b></p>
    <p>Stok Awal: <?= esc($batch['quantity_in']) ?> <?= esc($batch['unit']) ?></p>
    <p>Tanggal Masuk: <?= esc($batch['entry_date']) ?></p>
    <p>Lokasi: <?= esc($batch['location']) ?></p>

    <form action="<?= site_url('stock/save-stock/' . $batch['id']) ?>" method="post">
        <?= csrf_field() ?>

        <label>Stok Saat Ini</label>
        <input
            type="number"
            name="quantity_current"
            class="form-control"
            value="<?= old('quantity_current', $batch['quantity_current']) ?>"
            min="0"
            required
        >

        <label>Status Kesegaran</label>
        <select name="freshness_status" class="form-control" required>
            <?php foreach ($statuses as $status): ?>
                <option
                    value="<?= esc($status) ?>"
                    <?= old('freshness_status', $batch['freshness_status'] ?? 'Segar') === $status ? 'selected' : '' ?>
                >
                    <?= esc($status) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Catatan</label>
        <textarea name="note" class="form-control"><?= old('note', $batch['note']) ?></textarea>

        <button type="submit" class="btn btn-primary">
            Update Data
        </button>

        <a href="<?= site_url('stock') ?>" class="btn btn-soft">
            Kembali
        </a>
    </form>
</div>

<?= $this->endSection() ?>