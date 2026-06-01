<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Freshbar') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= base_url('assets/css/freshbar.css?v=70') ?>">
</head>
<body>

<?php
    $successMessage = session()->getFlashdata('success');
    $errorMessage = session()->getFlashdata('error');
?>

<?php if ($successMessage || $errorMessage): ?>
    <div class="toast-wrapper">
        <?php if ($successMessage): ?>
            <div class="toast-message toast-success">
                <div class="toast-icon">
                    <span>✓</span>
                </div>

                <div class="toast-content">
                    <p><?= esc($successMessage) ?></p>
                </div>

                <button type="button" class="toast-close" onclick="closeToast(this)">
                    ×
                </button>

                <div class="toast-progress"></div>
            </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="toast-message toast-error">
                <div class="toast-icon">
                    <span>!</span>
                </div>

                <div class="toast-content">
                    <p><?= esc($errorMessage) ?></p>
                </div>

                <button type="button" class="toast-close" onclick="closeToast(this)">
                    ×
                </button>

                <div class="toast-progress"></div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (session()->get('logged_in')): ?>
    <?php
        $role = session()->get('role');
        $name = session()->get('name');
        $uri = uri_string();

        if (! function_exists('freshbar_active')) {
            function freshbar_active($patterns, $uri)
            {
                foreach ((array) $patterns as $pattern) {
                    if ($uri === $pattern || str_starts_with($uri, $pattern . '/')) {
                        return 'active';
                    }
                }

                return '';
            }
        }

        if (! function_exists('freshbar_icon')) {
            function freshbar_icon($name)
            {
                $icons = [
                    'dashboard' => '
                        <svg viewBox="0 0 24 24" class="icon-svg" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 11.5L12 4l9 7.5"></path>
                            <path d="M5 10.5V20h14v-9.5"></path>
                            <path d="M9 20v-6h6v6"></path>
                        </svg>
                    ',
                    'products' => '
                        <svg viewBox="0 0 24 24" class="icon-svg" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 8l-9-5-9 5 9 5 9-5z"></path>
                            <path d="M3 8v8l9 5 9-5V8"></path>
                            <path d="M12 13v8"></path>
                        </svg>
                    ',
                    'staff' => '
                        <svg viewBox="0 0 24 24" class="icon-svg" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9.5" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    ',
                    'stock' => '
                        <svg viewBox="0 0 24 24" class="icon-svg" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 7h16v13H4z"></path>
                            <path d="M4 7l2-4h12l2 4"></path>
                            <path d="M12 11v5"></path>
                            <path d="M9.5 13.5L12 16l2.5-2.5"></path>
                        </svg>
                    ',
                    'scan' => '
                        <svg viewBox="0 0 24 24" class="icon-svg" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 7V5a1 1 0 0 1 1-1h2"></path>
                            <path d="M17 4h2a1 1 0 0 1 1 1v2"></path>
                            <path d="M20 17v2a1 1 0 0 1-1 1h-2"></path>
                            <path d="M7 20H5a1 1 0 0 1-1-1v-2"></path>
                            <path d="M7 12h10"></path>
                            <path d="M8 9v6"></path>
                            <path d="M11 9v6"></path>
                            <path d="M15 9v6"></path>
                        </svg>
                    ',
                    'report' => '
                        <svg viewBox="0 0 24 24" class="icon-svg" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 3h9l5 5v13H6z"></path>
                            <path d="M14 3v6h6"></path>
                            <path d="M9 17v-4"></path>
                            <path d="M12 17v-7"></path>
                            <path d="M15 17v-2"></path>
                        </svg>
                    ',
                    'logout' => '
                        <svg viewBox="0 0 24 24" class="icon-svg" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 17l5-5-5-5"></path>
                            <path d="M15 12H3"></path>
                            <path d="M21 19V5a2 2 0 0 0-2-2h-6"></path>
                        </svg>
                    ',
                ];

                return $icons[$name] ?? '';
            }
        }
    ?>

    <div class="app-shell">
        <aside class="sidenav">
            <div class="sidebar-brand">
                <div class="sidebar-logo-box">
                    <img
                        src="<?= base_url('assets/img/logo-freshbar.png') ?>"
                        alt="Logo Freshbar"
                        class="sidebar-logo-img"
                    >
                </div>

                <div class="sidebar-title">
                    <h2>Freshbar</h2>
                    <p>Freshness Stock System</p>
                </div>
            </div>

            <div class="menu-label">MENU</div>

            <a class="nav-link <?= freshbar_active('dashboard', $uri) ?>" href="<?= site_url('dashboard') ?>">
                <span class="nav-icon"><?= freshbar_icon('dashboard') ?></span>
                <span>Dashboard</span>
            </a>

            <?php if ($role === 'admin'): ?>
                <a class="nav-link <?= freshbar_active('admin/staff', $uri) ?>" href="<?= site_url('admin/staff') ?>">
                    <span class="nav-icon"><?= freshbar_icon('staff') ?></span>
                    <span>Data Karyawan</span>
                </a>
            <?php endif; ?>

            <a class="nav-link <?= freshbar_active('stock', $uri) ?>" href="<?= site_url('stock') ?>">
                <span class="nav-icon"><?= freshbar_icon('stock') ?></span>
                <span>Produk & Barang Masuk</span>
            </a>

            <a class="nav-link <?= freshbar_active('scan', $uri) ?>" href="<?= site_url('scan') ?>">
                <span class="nav-icon"><?= freshbar_icon('scan') ?></span>
                <span>Scan Barcode</span>
            </a>

            <a class="nav-link <?= freshbar_active('sales', $uri) ?>" href="<?= site_url('sales') ?>">
                <span class="nav-icon"><?= freshbar_icon('scan') ?></span> <!-- Reusing scan icon -->
                <span>Pencatatan Penjualan (POS)</span>
            </a>

            <?php if ($role === 'admin'): ?>
                <a class="nav-link <?= freshbar_active('admin/sales', $uri) ?>" href="<?= site_url('admin/sales') ?>">
                    <span class="nav-icon"><?= freshbar_icon('report') ?></span>
                    <span>Laporan Penjualan</span>
                </a>

                <a class="nav-link <?= freshbar_active('admin/reports', $uri) ?>" href="<?= site_url('admin/reports') ?>">
                    <span class="nav-icon"><?= freshbar_icon('report') ?></span>
                    <span>Laporan Stok</span>
                </a>
            <?php endif; ?>

            <a class="nav-link logout-link" href="<?= site_url('logout') ?>">
                <span class="nav-icon"><?= freshbar_icon('logout') ?></span>
                <span>Logout</span>
            </a>

            <div class="sidebar-user-info">
                <div class="sidebar-user-name">
                    <?= esc($name) ?>
                </div>

                <div class="sidebar-user-role">
                    <?= esc(ucfirst($role)) ?>
                </div>
            </div>
        </aside>

        <main class="content">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
<?php else: ?>
    <?= $this->renderSection('content') ?>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toastMessages = document.querySelectorAll('.toast-message');

        toastMessages.forEach(function (toast) {
            setTimeout(function () {
                hideToast(toast);
            }, 4200);
        });
    });

    function closeToast(button) {
        const toast = button.closest('.toast-message');
        hideToast(toast);
    }

    function hideToast(toast) {
        if (!toast) {
            return;
        }

        toast.classList.add('toast-hide');

        setTimeout(function () {
            toast.remove();
        }, 350);
    }
</script>

</body>
</html>