<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    .scan-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.045);
    }

    .scan-header {
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 20px;
        margin-bottom: 24px;
    }

    .scan-header h2 {
        margin: 0 0 8px;
        font-size: 22px;
        color: #111827;
    }

    .scan-header p {
        margin: 0;
        color: #6b7280;
        font-size: 15px;
        line-height: 1.6;
    }

    .scan-grid {
        display: grid;
        grid-template-columns: 420px 1fr;
        gap: 28px;
        align-items: start;
    }

    .camera-box {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #f8fbfa;
        padding: 16px;
    }

    .video-frame {
        width: 100%;
        height: 280px;
        border-radius: 14px;
        background: #082f2c;
        overflow: hidden;
        position: relative;
    }

    .video-frame video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .scan-guide {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 82%;
        height: 38%;
        transform: translate(-50%, -50%);
        border: 2px solid rgba(255, 255, 255, 0.9);
        border-radius: 12px;
        pointer-events: none;
    }

    .camera-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: rgba(255, 255, 255, 0.82);
        font-size: 14px;
        padding: 20px;
    }

    .camera-tools {
        margin-top: 14px;
        display: grid;
        gap: 12px;
    }

    .camera-tools label {
        font-size: 14px;
        font-weight: 800;
        color: #111827;
    }

    .camera-select {
        width: 100%;
        height: 46px;
        border: 1px solid #d8dee7;
        background: #f1f5f9;
        border-radius: 13px;
        padding: 0 14px;
        font-size: 14px;
        outline: none;
    }

    .camera-select:focus {
        background: #ffffff;
        border-color: #0f9f95;
    }

    .camera-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .manual-box {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #f8fbfa;
        padding: 22px;
    }

    .manual-box h3 {
        margin: 0 0 8px;
        font-size: 20px;
        color: #111827;
    }

    .manual-box p {
        margin: 0 0 20px;
        color: #6b7280;
        font-size: 15px;
        line-height: 1.6;
    }

    .manual-box label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 800;
        color: #111827;
    }

    .manual-box .form-control {
        margin-bottom: 16px;
    }

    .status-box {
        margin-top: 14px;
        padding: 12px 14px;
        border-radius: 13px;
        background: #e9f8f6;
        color: #08746d;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.5;
    }

    .scan-tip {
        margin-top: 16px;
        padding: 13px 15px;
        border-radius: 13px;
        background: #ecfdf5;
        color: #065f46;
        font-size: 14px;
        line-height: 1.6;
    }

    .btn-secondary {
        background: #e9f8f6;
        color: #08746d;
    }

    .btn-secondary:hover {
        background: #d8f1ee;
    }

    @media (max-width: 1000px) {
        .scan-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .scan-card {
            padding: 22px;
        }

        .video-frame {
            height: 230px;
        }
    }
</style>

<div class="scan-card">
    <div class="scan-header">
        <h2>Scan Barcode Produk</h2>
        <p>
            Arahkan barcode ke tengah kotak kamera. Jika barcode belum terbaca,
            masukkan kode secara manual.
        </p>
    </div>

    <div class="scan-grid">
        <div class="camera-box">
            <div class="video-frame">
                <video id="video" muted playsinline></video>
                <div class="scan-guide"></div>
                <div class="camera-placeholder" id="cameraPlaceholder">
                    Klik “Mulai Kamera” untuk mengaktifkan scanner.
                </div>
            </div>

            <div class="camera-tools">
                <div>
                    <label for="cameraSelect">Kamera</label>
                    <select id="cameraSelect" class="camera-select">
                        <option value="">Belum ada kamera</option>
                    </select>
                </div>

                <div class="camera-actions">
                    <button type="button" class="btn btn-primary" id="startButton">
                        Mulai Kamera
                    </button>

                    <button type="button" class="btn btn-secondary" id="stopButton">
                        Stop Kamera
                    </button>
                </div>

                <div class="status-box" id="scanStatus">
                    Scanner belum aktif.
                </div>
            </div>
        </div>

        <div class="manual-box">
            <h3>Cek Manual</h3>
            <p>
                Masukkan kode barcode yang tertulis pada label produk Freshbar.
            </p>

            <form action="<?= site_url('scan/check') ?>" method="post" id="scanForm">
                <?= csrf_field() ?>

                <label for="barcodeInput">Kode Barcode</label>
                <input
                    type="text"
                    name="barcode"
                    id="barcodeInput"
                    class="form-control"
                    placeholder="Contoh: FRB-20260526101839-132"
                    required
                >

                <button type="submit" class="btn btn-primary">
                    Cek Kesegaran
                </button>
            </form>

            <div class="scan-tip">
                Tips: barcode garis seperti CODE128 lebih mudah terbaca kalau label cukup besar,
                terang, tidak buram, dan kamera tidak terlalu dekat.
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/@zxing/library@latest"></script>

<script>
    const video = document.getElementById('video');
    const cameraSelect = document.getElementById('cameraSelect');
    const startButton = document.getElementById('startButton');
    const stopButton = document.getElementById('stopButton');
    const barcodeInput = document.getElementById('barcodeInput');
    const scanForm = document.getElementById('scanForm');
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

    function getResultText(result) {
        if (!result) {
            return '';
        }

        if (typeof result.getText === 'function') {
            return result.getText();
        }

        return result.text || '';
    }

    async function askCameraPermission() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            throw new Error('Browser tidak mendukung kamera.');
        }

        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'environment',
                width: { ideal: 1280 },
                height: { ideal: 720 }
            },
            audio: false
        });

        stream.getTracks().forEach(track => track.stop());
    }

    async function loadCameras() {
        if (typeof ZXing === 'undefined') {
            setStatus('Library scanner gagal dimuat. Pastikan internet aktif.');
            return;
        }

        if (!codeReader) {
            const hints = new Map();

            hints.set(ZXing.DecodeHintType.POSSIBLE_FORMATS, [
                ZXing.BarcodeFormat.CODE_128,
                ZXing.BarcodeFormat.CODE_39,
                ZXing.BarcodeFormat.CODE_93,
                ZXing.BarcodeFormat.EAN_13,
                ZXing.BarcodeFormat.EAN_8,
                ZXing.BarcodeFormat.UPC_A,
                ZXing.BarcodeFormat.UPC_E,
                ZXing.BarcodeFormat.QR_CODE
            ]);

            hints.set(ZXing.DecodeHintType.TRY_HARDER, true);

            codeReader = new ZXing.BrowserMultiFormatReader(hints, 500);
        }

        try {
            await askCameraPermission();

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

            const backCamera = devices.find(device => {
                return device.label.toLowerCase().includes('back') ||
                       device.label.toLowerCase().includes('rear') ||
                       device.label.toLowerCase().includes('environment');
            });

            selectedDeviceId = backCamera ? backCamera.deviceId : devices[0].deviceId;
            cameraSelect.value = selectedDeviceId;

            setStatus('Kamera siap digunakan.');
        } catch (error) {
            console.error(error);
            setStatus('Izin kamera belum diberikan atau kamera tidak tersedia.');
        }
    }

    async function startScanner() {
        if (isScanning) {
            return;
        }

        if (typeof ZXing === 'undefined') {
            alert('Library scanner gagal dimuat. Pastikan internet aktif.');
            setStatus('Library scanner gagal dimuat.');
            return;
        }

        if (!codeReader) {
            await loadCameras();
        }

        selectedDeviceId = cameraSelect.value || selectedDeviceId;

        if (!selectedDeviceId) {
            await loadCameras();
            selectedDeviceId = cameraSelect.value || selectedDeviceId;
        }

        if (!selectedDeviceId) {
            setStatus('Tidak ada kamera yang bisa dipakai.');
            return;
        }

        try {
            scanLocked = false;
            isScanning = true;
            showPlaceholder(false);
            setStatus('Scanner aktif. Arahkan barcode ke tengah kotak.');

            codeReader.decodeFromVideoDevice(
                selectedDeviceId,
                video,
                function (result, error) {
                    if (result && !scanLocked) {
                        const decodedText = getResultText(result);

                        if (decodedText) {
                            scanLocked = true;
                            barcodeInput.value = decodedText;
                            setStatus('Barcode terbaca: ' + decodedText);

                            stopScanner(false);

                            setTimeout(function () {
                                scanForm.submit();
                            }, 500);
                        }
                    }

                    if (error && !(error instanceof ZXing.NotFoundException)) {
                        console.log(error);
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

    function stopScanner(showMessage = true) {
        try {
            if (codeReader) {
                codeReader.reset();
            }

            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }

            isScanning = false;
            showPlaceholder(true);

            if (showMessage) {
                setStatus('Scanner dihentikan.');
            }
        } catch (error) {
            console.error(error);
            setStatus('Scanner gagal dihentikan.');
        }
    }

    cameraSelect.addEventListener('change', function () {
        selectedDeviceId = this.value;

        if (isScanning) {
            stopScanner(false);

            setTimeout(function () {
                startScanner();
            }, 400);
        }
    });

    startButton.addEventListener('click', startScanner);

    stopButton.addEventListener('click', function () {
        stopScanner(true);
    });

    document.addEventListener('DOMContentLoaded', function () {
        loadCameras();
    });

    window.addEventListener('beforeunload', function () {
        stopScanner(false);
    });
</script>

<?= $this->endSection() ?>