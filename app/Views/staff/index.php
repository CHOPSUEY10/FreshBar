<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-card">
    <div class="page-card-header">
        <div class="page-card-title">
            <h2>Daftar Staff Gudang</h2>
            <p>Kelola akun staff gudang yang dapat mengakses sistem Freshbar.</p>
        </div>

        <div class="page-card-actions">
            <a href="<?= site_url('admin/staff/create') ?>" class="btn btn-primary">
                + Tambah Staff
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th>Nama Staff</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Dibuat</th>
                    <th class="col-action">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($staffs)): ?>
                    <?php foreach ($staffs as $index => $staff): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($staff['name']) ?></td>
                            <td><?= esc($staff['username']) ?></td>
                            <td>
                                <span class="badge badge-info">
                                    <?= esc(ucfirst($staff['role'])) ?>
                                </span>
                            </td>
                            <td>
                                <?= !empty($staff['created_at']) ? esc($staff['created_at']) : '-' ?>
                            </td>
                            <td>
                                <div class="action-row">
                                    <a class="btn btn-soft btn-sm" href="<?= site_url('admin/staff/edit/' . $staff['id']) ?>">
                                        Edit
                                    </a>

                                    <form action="<?= site_url('admin/staff/delete/' . $staff['id']) ?>" method="post" onsubmit="return confirm('Hapus staff ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-sm">
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
                            Belum ada data staff gudang.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>