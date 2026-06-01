<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$isEdit = $staff !== null;
$action = $isEdit ? site_url('admin/staff/update/' . $staff['id']) : site_url('admin/staff/store');
?>

<div class="card">
    <form action="<?= $action ?>" method="post">
        <?= csrf_field() ?>

        <label>Nama Staff</label>
        <input type="text" name="name" class="form-control" value="<?= esc($staff['name'] ?? '') ?>" required>

        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?= esc($staff['username'] ?? '') ?>" required>

        <label>Password <?= $isEdit ? '(kosongkan jika tidak diganti)' : '' ?></label>
        <input type="password" name="password" class="form-control" <?= $isEdit ? '' : 'required' ?>>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= site_url('admin/staff') ?>" class="btn btn-soft">Kembali</a>
    </form>
</div>

<?= $this->endSection() ?>