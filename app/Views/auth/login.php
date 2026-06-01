<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="login-page">
    <div class="login-container">
        <div class="login-left">
            <div class="login-form-box">
                <div class="login-brand">
                    <div class="login-logo-box">
                        <img
                            src="<?= base_url('assets/img/logo-freshbar.png') ?>"
                            alt="Logo Freshbar"
                            class="login-logo-img"
                        >
                    </div>

                    <div class="login-brand-text">
                        <h3>Freshbar</h3>
                        <p>Freshness Stock System</p>
                    </div>
                </div>

                <h1 class="login-title">Sign in</h1>

                <p class="login-subtitle">
                    Masukan Username dan Password
                </p>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        placeholder="Username"
                        value="<?= old('username') ?>"
                        required
                        autofocus
                    >

                    <div class="password-wrap">
                        <input
                            type="password"
                            name="password"
                            id="passwordInput"
                            class="form-control"
                            placeholder="Password"
                            required
                        >

                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            👁
                        </button>
                    </div>

                    <button type="submit" class="btn btn-primary btn-login">
                        SIGN IN
                    </button>
                </form>
            </div>
        </div>

        <div class="login-right">
            <div class="login-welcome">
                <h1>Selamat Datang Admin dan Staff!</h1>

                <p>
                    Di sistem Freshbar, admin dan staff gudang dapat mengelola
                    data produk, stok barang masuk, barcode, serta memantau
                    status kesegaran buah dan sayur secara lebih mudah, rapi,
                    dan akurat.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('passwordInput');
        const toggleButton = document.querySelector('.password-toggle');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.textContent = '🙈';
        } else {
            passwordInput.type = 'password';
            toggleButton.textContent = '👁';
        }
    }
</script>

<?= $this->endSection() ?>