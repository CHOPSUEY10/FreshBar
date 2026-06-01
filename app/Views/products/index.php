<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-card">
    <div class="page-card-header">
        <div class="page-card-title">
            <h2>Daftar Produk</h2>
            <p>Kelola data buah dan sayur beserta masa kesegarannya.</p>
        </div>

        <div class="page-card-actions">
            <a href="<?= site_url('admin/products/create') ?>" class="btn btn-primary">
                + Tambah Produk
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th>Nama Produk</th>
                    <th>Jenis</th>
                    <th>Satuan</th>
                    <th>Masa Segar</th>
                    <th class="col-action">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $index => $product): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($product['name']) ?></td>
                            <td><?= esc($product['type']) ?></td>
                            <td><?= esc($product['unit']) ?></td>
                            <td><?= esc($product['shelf_life_days']) ?> hari</td>
                            <td>
                                <div class="action-row">
                                    <a class="btn btn-soft btn-sm" href="<?= site_url('admin/products/edit/' . $product['id']) ?>">
                                        Edit
                                    </a>

                                    <form action="<?= site_url('admin/products/delete/' . $product['id']) ?>" method="post" onsubmit="return confirm('Hapus produk ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-danger btn-sm" type="submit">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="table-empty">
                            Belum ada data produk.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>