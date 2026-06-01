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
                    <th class="stock-col-product">Produk</th>
                    <th class="stock-col-date">Tanggal Masuk</th>
                    <th class="stock-col-qty">Stok Terkini</th>
                    <th class="stock-col-status">Status Kesegaran</th>
                    <th class="stock-col-location">Lokasi Batch</th>
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
                        <td colspan="7" class="table-empty">
                            Belum ada barang masuk.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (session()->get('role') === 'admin'): ?>
<div class="page-card" style="margin-top: 24px;">
    <div class="page-card-header">
        <div class="page-card-title">
            <h2>Data Master Produk</h2>
            <p>Kelola identitas dasar, harga, dan total stok keseluruhan (Semua Batch).</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th>Nama Produk</th>
                    <th>Barcode</th>
                    <th>Harga Terakhir (Rp)</th>
                    <th>Masa Segar</th>
                    <th>Total Stok Gudang</th>
                    <th>Lokasi Penyimpanan</th>
                    <th class="col-action">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $index => $product): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($product['name']) ?></td>
                            <td>
                                <?php if (!empty($product['barcode'])): ?>
                                    <strong><?= esc($product['barcode']) ?></strong>
                                <?php else: ?>
                                    <span class="badge badge-warning">Belum ada</span>
                                <?php endif; ?>
                            </td>
                            <td><?= number_format($product['price'] ?? 0, 0, ',', '.') ?></td>
                            <td><?= esc($product['shelf_life_days']) ?> hari</td>
                            
                            <!-- Total Stock from getWithStock query -->
                            <td>
                                <strong><?= esc($product['total_stock'] ?? 0) ?></strong> <?= esc($product['unit']) ?>
                            </td>
                            
                            <!-- Aggregated Locations from getWithStock query -->
                            <td>
                                <?php if (!empty($product['locations'])): ?>
                                    <?= esc($product['locations']) ?>
                                <?php else: ?>
                                    <span style="color: #9ca3af;">Belum ada stok</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="action-row">
                                    <a class="btn btn-soft btn-sm" href="<?= site_url('admin/products/edit/' . $product['id']) ?>">
                                        Edit
                                    </a>
                                    
                                    <?php if (!empty($product['barcode'])): ?>
                                    <a class="btn btn-primary btn-sm" style="color:white; padding: 8px 12px; height: 40px; margin-right: 5px;" href="<?= site_url('barcode/print_product/' . $product['id']) ?>" title="Print Barcode">
                                        <svg viewBox="0 0 24 24" class="icon-svg" style="width: 16px; height: 16px; margin: 0; display: inline-block;" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 7V5a1 1 0 0 1 1-1h2"></path>
                                            <path d="M17 4h2a1 1 0 0 1 1 1v2"></path>
                                            <path d="M20 17v2a1 1 0 0 1-1 1h-2"></path>
                                            <path d="M7 20H5a1 1 0 0 1-1-1v-2"></path>
                                            <path d="M7 12h10"></path>
                                            <path d="M8 9v6"></path>
                                            <path d="M11 9v6"></path>
                                            <path d="M15 9v6"></path>
                                        </svg>
                                    </a>
                                    <?php endif; ?>

                                    <form action="<?= site_url('admin/products/delete/' . $product['id']) ?>" method="post" onsubmit="return confirm('Hapus produk ini beserta seluruh riwayat stoknya?')">
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
                        <td colspan="8" class="table-empty">Belum ada data produk.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>