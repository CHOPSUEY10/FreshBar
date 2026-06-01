<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-card">
    <div class="page-card-header">
        <div class="page-card-title">
            <h2>Daftar Barang Masuk</h2>
            <p>Kelola batch barang masuk, stok, barcode, dan status kesegaran.</p>
        </div>

        <div class="page-card-actions">
            <a href="<?= site_url('stock/create') ?>" class="btn btn-primary">
                + Input Barang Masuk
            </a>
        </div>
    </div>

    <div class="table-responsive stock-table-scroll">
        <table class="table stock-table">
            <thead>
                <tr>
                    <th class="stock-col-no">No</th>
                    <th class="stock-col-barcode">Barcode</th>
                    <th class="stock-col-product">Produk</th>
                    <th class="stock-col-date">Tanggal Masuk</th>
                    <th class="stock-col-qty">Stok</th>
                    <th class="stock-col-status">Status</th>
                    <th class="stock-col-location">Lokasi</th>
                    <th class="stock-col-action">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($batches)): ?>
                    <?php foreach ($batches as $index => $batch): ?>
                        <?php
                            $status = $batch['freshness_status'] ?? null;

                            if ($status) {
                                $fresh = freshness_status_detail($status);
                            } else {
                                $fresh = freshness_status($batch['entry_date'], $batch['shelf_life_days']);
                            }
                        ?>

                        <tr>
                            <td class="stock-col-no">
                                <?= $index + 1 ?>
                            </td>

                            <td class="stock-col-barcode">
                                <?= esc($batch['barcode']) ?>
                            </td>

                            <td class="stock-col-product">
                                <?= esc($batch['product_name']) ?>
                            </td>

                            <td class="stock-col-date">
                                <?= esc($batch['entry_date']) ?>
                            </td>

                            <td class="stock-col-qty">
                                <?= esc($batch['quantity_current']) ?>
                                <?= esc($batch['unit']) ?>
                            </td>

                            <td class="stock-col-status">
                                <span class="badge <?= esc($fresh['badge']) ?>">
                                    <?= esc($fresh['status']) ?>
                                </span>
                            </td>

                            <td class="stock-col-location">
                                <?= esc($batch['location']) ?>
                            </td>

                            <td class="stock-col-action stock-action-cell">
                                <div class="stock-action-row">
                                    <a
                                        href="<?= site_url('barcode/print/' . $batch['id']) ?>"
                                        class="btn btn-soft btn-sm stock-action-btn"
                                    >
                                        Barcode
                                    </a>

                                    <a
                                        href="<?= site_url('stock/update/' . $batch['id']) ?>"
                                        class="btn btn-soft btn-sm stock-action-btn"
                                    >
                                        Update
                                    </a>

                                    <form
                                        action="<?= site_url('stock/delete/' . $batch['id']) ?>"
                                        method="post"
                                        class="stock-delete-form"
                                        onsubmit="return confirm('Yakin ingin menghapus data barang masuk ini? Data yang sudah dihapus tidak bisa dikembalikan.');"
                                    >
                                        <?= csrf_field() ?>

                                        <button type="submit" class="btn btn-danger btn-sm stock-action-btn">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="table-empty">
                            Belum ada barang masuk.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>