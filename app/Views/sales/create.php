<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-card">
    <div class="page-card-header">
        <div class="page-card-title">
            <h2>Pencatatan Penjualan (POS)</h2>
            <p>Scan barcode produk untuk menambah ke keranjang belanja.</p>
        </div>
    </div>

    <div class="dashboard-grid" style="grid-template-columns: 1fr; gap: 20px;">
        <!-- Container Atas: Kamera & Manual -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
            <!-- Camera Scanner -->
            <div class="dashboard-card" style="padding: 20px;">
                <h3 style="margin-top: 0;">Kamera Scanner</h3>
                <div style="width: 100%; height: 260px; background: #082f2c; border-radius: 14px; position: relative; overflow: hidden; margin-bottom: 14px;">
                    <video id="video" muted playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                    <div id="cameraPlaceholder" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.8); text-align: center; padding: 20px;">
                        Klik "Mulai Kamera" untuk mengaktifkan scanner.
                    </div>
                </div>
                <select id="cameraSelect" class="form-control" style="margin-bottom: 10px;">
                    <option value="">Belum ada kamera</option>
                </select>
                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn btn-primary" id="startButton">Mulai Kamera</button>
                    <button type="button" class="btn btn-soft" id="stopButton" style="background: #e9f8f6; color: #08746d; border: none;">Stop Kamera</button>
                </div>
                <div id="scanStatus" style="margin-top: 12px; font-weight: bold; color: #08746d; font-size: 14px; background: #e9f8f6; padding: 10px; border-radius: 10px;">Scanner belum aktif.</div>
            </div>

            <!-- Manual Input Fallback -->
            <div class="dashboard-card" style="padding: 20px;">
                <h3 style="margin-top: 0;">Input Manual (Fallback)</h3>
                <p style="color: #6b7280; margin-bottom: 15px;">Jika kamera sulit membaca barcode, ketik kode secara manual di sini.</p>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                    <input type="text" id="barcodeInput" class="form-control" placeholder="Contoh: FRB-..." autofocus autocomplete="off" style="font-size: 1.1rem; padding: 10px;">
                    <button type="button" class="btn btn-primary" onclick="processBarcode()">Tambah</button>
                </div>
                <div id="scanFeedback" style="color: #e74c3c; font-weight: bold; margin-bottom: 10px;"></div>
            </div>
        </div>
    </div>

    <div class="page-card" style="margin-top: 20px;">
        <h3 style="margin-top: 0;">Keranjang Belanja</h3>
        <div class="table-responsive">
            <table class="table" id="cartTable">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th>Barcode</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th class="col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody id="cartBody">
                    <tr id="emptyCartRow">
                        <td colspan="7" class="table-empty">Keranjang kosong.</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align: right; font-size: 1.2rem;">Total:</th>
                        <th colspan="2" style="font-size: 1.2rem; color: #2ecc71;" id="totalAmountLabel">Rp 0</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div style="margin-top: 20px; text-align: right;">
            <button type="button" class="btn btn-primary" style="font-size: 1.2rem; padding: 10px 30px;" onclick="checkout()">Selesaikan Penjualan (Checkout)</button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/@zxing/library@latest"></script>
<script>
    let cart = [];
    const scanUrl = '<?= site_url('sales/scanBarcode') ?>';
    const storeUrl = '<?= site_url('sales/store') ?>';
    const csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    const barcodeInput = document.getElementById('barcodeInput');
    const feedback = document.getElementById('scanFeedback');

    // --- KAMERA SCANNER LOGIC ---
    const video = document.getElementById('video');
    const cameraSelect = document.getElementById('cameraSelect');
    const startButton = document.getElementById('startButton');
    const stopButton = document.getElementById('stopButton');
    const scanStatus = document.getElementById('scanStatus');
    const cameraPlaceholder = document.getElementById('cameraPlaceholder');

    let codeReader = null;
    let selectedDeviceId = null;
    let isScanning = false;
    let scanLocked = false;

    function setStatus(message) {
        scanStatus.textContent = message;
    }

    function showPlaceholder(show) {
        cameraPlaceholder.style.display = show ? 'flex' : 'none';
    }

    async function loadCameras() {
        if (typeof ZXing === 'undefined') {
            setStatus('Library scanner gagal dimuat. Pastikan internet aktif.');
            return;
        }

        if (!codeReader) {
            codeReader = new ZXing.BrowserMultiFormatReader();
        }

        try {
            const devices = await codeReader.listVideoInputDevices();
            cameraSelect.innerHTML = '';

            if (!devices || devices.length === 0) {
                cameraSelect.innerHTML = '<option value="">Kamera tidak ditemukan</option>';
                setStatus('Kamera tidak ditemukan.');
                return;
            }

            devices.forEach((device, index) => {
                const option = document.createElement('option');
                option.value = device.deviceId;
                option.textContent = device.label || `Kamera ${index + 1}`;
                cameraSelect.appendChild(option);
            });

            const backCamera = devices.find(device => device.label.toLowerCase().includes('back') || device.label.toLowerCase().includes('environment'));
            selectedDeviceId = backCamera ? backCamera.deviceId : devices[0].deviceId;
            cameraSelect.value = selectedDeviceId;

            setStatus('Kamera siap digunakan.');
        } catch (error) {
            console.error(error);
            setStatus('Kamera tidak tersedia atau belum diizinkan.');
        }
    }

    async function startScanner() {
        if (isScanning) return;

        if (!codeReader) await loadCameras();
        selectedDeviceId = cameraSelect.value || selectedDeviceId;

        if (!selectedDeviceId) {
            setStatus('Tidak ada kamera yang bisa dipakai.');
            return;
        }

        try {
            scanLocked = false;
            isScanning = true;
            showPlaceholder(false);
            setStatus('Scanner aktif. Arahkan barcode ke layar.');

            codeReader.decodeFromVideoDevice(
                selectedDeviceId,
                video,
                function (result, error) {
                    if (result && !scanLocked) {
                        const decodedText = result.text || result.getText();
                        if (decodedText) {
                            scanLocked = true;
                            barcodeInput.value = decodedText;
                            setStatus('Barcode terbaca: ' + decodedText);
                            
                            // Langsung proses tambah ke keranjang
                            processBarcode().then(() => {
                                // Unlock scanner setelah beberapa saat untuk scan berikutnya
                                setTimeout(() => { scanLocked = false; setStatus('Scanner aktif. Arahkan barcode ke layar.'); }, 1500);
                            });
                        }
                    }
                }
            );
        } catch (error) {
            console.error(error);
            isScanning = false;
            showPlaceholder(true);
            setStatus('Scanner gagal dibuka.');
        }
    }

    function stopScanner() {
        if (codeReader) codeReader.reset();
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }
        isScanning = false;
        showPlaceholder(true);
        setStatus('Scanner dihentikan.');
    }

    cameraSelect.addEventListener('change', function () {
        selectedDeviceId = this.value;
        if (isScanning) {
            stopScanner();
            setTimeout(startScanner, 400);
        }
    });

    startButton.addEventListener('click', startScanner);
    stopButton.addEventListener('click', stopScanner);

    document.addEventListener('DOMContentLoaded', loadCameras);
    window.addEventListener('beforeunload', stopScanner);

    // --- END KAMERA SCANNER LOGIC ---

    barcodeInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            processBarcode();
        }
    });

    function updateCsrf(newHash) {
        if(newHash) {
            csrfHash = newHash;
        }
    }

    async function processBarcode() {
        feedback.innerText = '';
        const barcode = barcodeInput.value.trim();
        
        if (!barcode) return;

        try {
            const formData = new FormData();
            formData.append('barcode', barcode);
            formData.append(csrfName, csrfHash);

            const response = await fetch(scanUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const result = await response.json();
            
            // CodeIgniter CSRF update workaround if returned by the framework
            if(result.csrf) {
                updateCsrf(result.csrf);
            }

            if (result.status === 'success') {
                addToCart(result.data);
                barcodeInput.value = '';
                barcodeInput.focus();
            } else {
                feedback.innerText = result.messages?.error || result.message || 'Gagal scan barcode.';
                barcodeInput.select();
            }
        } catch (error) {
            console.error(error);
            feedback.innerText = 'Terjadi kesalahan sistem.';
        }
    }

    function addToCart(data) {
        // Check if batch already in cart
        const index = cart.findIndex(item => item.batch_id === data.batch_id);
        
        if (index > -1) {
            if (cart[index].quantity + 1 > data.quantity_current) {
                feedback.innerText = `Stok tidak cukup! Sisa stok: ${data.quantity_current}`;
                return;
            }
            cart[index].quantity += 1;
        } else {
            if (data.quantity_current < 1) {
                feedback.innerText = 'Stok habis.';
                return;
            }
            cart.push({
                batch_id: data.batch_id,
                barcode: data.barcode,
                product_name: data.product_name,
                price: data.price,
                quantity: 1,
                max_qty: data.quantity_current
            });
        }
        
        renderCart();
    }

    function updateQty(index, qty) {
        qty = parseInt(qty);
        if (isNaN(qty) || qty < 1) qty = 1;
        
        if (qty > cart[index].max_qty) {
            alert('Stok maksimal adalah ' + cart[index].max_qty);
            qty = cart[index].max_qty;
        }

        cart[index].quantity = qty;
        renderCart();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function renderCart() {
        const body = document.getElementById('cartBody');
        const emptyRow = document.getElementById('emptyCartRow');
        const totalAmountLabel = document.getElementById('totalAmountLabel');
        
        body.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            body.appendChild(emptyRow);
            totalAmountLabel.innerText = 'Rp 0';
            return;
        }

        cart.forEach((item, index) => {
            const tr = document.createElement('tr');
            const subtotal = item.price * item.quantity;
            total += subtotal;

            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${item.barcode}</td>
                <td>${item.product_name}</td>
                <td>Rp ${item.price.toLocaleString('id-ID')}</td>
                <td>
                    <input type="number" value="${item.quantity}" min="1" max="${item.max_qty}" style="width: 70px;" class="form-control" onchange="updateQty(${index}, this.value)">
                </td>
                <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${index})">Batal</button>
                </td>
            `;
            body.appendChild(tr);
        });

        totalAmountLabel.innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    async function checkout() {
        if (cart.length === 0) {
            alert('Keranjang belanja kosong!');
            return;
        }

        if (!confirm('Selesaikan transaksi ini?')) return;

        try {
            const formData = new URLSearchParams();
            formData.append(csrfName, csrfHash);
            
            cart.forEach((item, index) => {
                formData.append(`cart[${index}][batch_id]`, item.batch_id);
                formData.append(`cart[${index}][quantity]`, item.quantity);
                formData.append(`cart[${index}][price]`, item.price);
                formData.append(`cart[${index}][barcode]`, item.barcode);
            });

            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData.toString()
            });

            const result = await response.json();
            
            if (result.status === 'success') {
                alert(result.message);
                cart = [];
                renderCart();
                window.location.reload(); // Refresh to update CSRF properly
            } else {
                alert(result.messages?.error || result.message || 'Gagal menyimpan transaksi.');
            }
        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan sistem.');
        }
    }
</script>

<?= $this->endSection() ?>
