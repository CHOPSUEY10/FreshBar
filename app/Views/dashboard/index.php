<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
    $role = session()->get('role');

    $totalProducts = (int) ($totalProducts ?? 0);
    $totalBatches = (int) ($totalBatches ?? 0);
    $fresh = (int) ($fresh ?? 0);
    $warning = (int) ($warning ?? 0);
    $bad = (int) ($bad ?? 0);
    $needCheck = $warning + $bad;
?>

<div class="dashboard-grid">
    <div class="stat">
        <h3>Total Produk</h3>
        <strong><?= esc($totalProducts) ?></strong>
    </div>

    <div class="stat">
        <h3>Batch Barang</h3>
        <strong><?= esc($totalBatches) ?></strong>
    </div>

    <div class="stat">
        <h3>Produk Segar</h3>
        <strong><?= esc($fresh) ?></strong>
    </div>

    <div class="stat">
        <h3>Perlu Dicek</h3>
        <strong><?= esc($needCheck) ?></strong>
    </div>
</div>

<div class="dashboard-card welcome-box">
    <?php if ($role === 'admin'): ?>
        <h2> <b>Selamat Datang <?=  $role ?> Freshbar</b> </h2>
    <?php else: ?>
       <h2> <b>Selamat Datang <?=  $role ?> Freshbar</b> </h2>
    <?php endif; ?>
</div>

<div class="dashboard-grid" style="margin-top: 20px; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
    <!-- Container for Stock Chart -->
    <div class="dashboard-card">
        <h3>Grafik Stock Masuk & Keluar (Tidak Fresh)</h3>
        <canvas id="stockChart" height="150"></canvas>
    </div>

    <!-- Container for Sales Chart -->
    <div class="dashboard-card">
        <h3>Grafik Penjualan Bulanan</h3>
        <canvas id="salesChart" height="150"></canvas>
    </div>
</div>

<!-- Toast Notifikasi (Jika ada produk tidak fresh) -->
<?php if ($bad > 0): ?>
    <div class="toast-wrapper" id="badStockToastWrapper">
        <div class="toast-message toast-error" id="badStockToast">
            <div class="toast-icon">
                <span>!</span>
            </div>
            <div class="toast-content">
                <p>Ada <?= esc($bad) ?> batch produk yang TIDAK SEGAR di gudang!</p>
            </div>
            <button type="button" class="toast-close" onclick="closeToast(this)">×</button>
            <div class="toast-progress"></div>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Init Chart
        const chartDataRaw = '<?= addslashes($chartData ?? '{}') ?>';
        if (chartDataRaw && chartDataRaw !== '{}') {
            const data = JSON.parse(chartDataRaw);
            
            // Stock Chart
            const ctxStock = document.getElementById('stockChart').getContext('2d');
            new Chart(ctxStock, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Stock Masuk',
                            data: data.stockIn,
                            backgroundColor: '#2ecc71',
                            borderRadius: 4
                        },
                        {
                            label: 'Stock Keluar (Tidak Fresh)',
                            data: data.stockOutBad,
                            backgroundColor: '#e74c3c',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });

            // Sales Chart
            const ctxSales = document.getElementById('salesChart').getContext('2d');
            new Chart(ctxSales, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Total Penjualan (Rp)',
                            data: data.salesTotal,
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        
        // Auto-hide bad stock toast after 5s
        const badToast = document.getElementById('badStockToast');
        if (badToast) {
            setTimeout(function() {
                hideToast(badToast);
            }, 5000);
        }
    });
</script>

<?= $this->endSection() ?>