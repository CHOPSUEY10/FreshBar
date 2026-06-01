<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #eef5f4;
            color: #111827;
        }

        /* Sembunyikan toolbar debug CodeIgniter */
        .ci-debug-toolbar,
        .ci-toolbar,
        .debug-toolbar,
        #debugbar,
        #debug-icon,
        .debugbar,
        .kint,
        [class*="debug"],
        [id*="debug"],
        iframe[src*="debugbar"] {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            width: 0 !important;
            height: 0 !important;
        }

        .screen-page {
            min-height: 100vh;
            background: #eef5f4;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .screen-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 18px;
        }

        .label-card {
            width: 300px;
            background: #ffffff;
            border-radius: 14px;
            padding: 16px 14px 12px;
            text-align: center;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
        }

        .brand {
            margin: 0 0 6px;
            color: #0f9f95;
            font-size: 20px;
            font-weight: 800;
            line-height: 1.1;
        }

        .product-name {
            margin: 0 0 4px;
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            line-height: 1.2;
        }

        .info-text {
            margin: 0 0 8px;
            font-size: 12px;
            color: #374151;
            line-height: 1.3;
        }

        .barcode-wrap {
            width: 100%;
            overflow: hidden;
            margin: 6px 0 8px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .barcode-box {
            width: 100%;
            overflow: hidden;
        }

        #barcode {
            width: 100% !important;
            max-width: 100% !important;
            height: 50px !important;
            display: block;
        }

        .barcode-code {
            margin: 5px 0 3px;
            font-size: 12px;
            font-weight: 700;
            color: #111827;
            word-break: break-word;
            line-height: 1.25;
        }

        .location-text {
            margin: 0;
            font-size: 12px;
            color: #374151;
            line-height: 1.25;
        }

        .screen-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 125px;
            height: 40px;
            padding: 0 18px;
            border: none;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background: #0f9f95;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #08746d;
        }

        .btn-secondary {
            background: #e9f8f6;
            color: #08746d;
        }

        .btn-secondary:hover {
            background: #d8f1ee;
        }

        /* =========================
           PRINT LABEL KECIL
        ========================= */

        @page {
            size: 80mm 45mm;
            margin: 0;
        }

        @media print {
            html,
            body {
                width: 80mm !important;
                height: 45mm !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                overflow: hidden !important;
            }

            .ci-debug-toolbar,
            .ci-toolbar,
            .debug-toolbar,
            #debugbar,
            #debug-icon,
            .debugbar,
            .kint,
            [class*="debug"],
            [id*="debug"],
            iframe[src*="debugbar"] {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
                width: 0 !important;
                height: 0 !important;
            }

            .screen-page {
                width: 80mm !important;
                height: 45mm !important;
                min-height: 45mm !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                display: block !important;
                overflow: hidden !important;
            }

            .screen-wrapper {
                width: 80mm !important;
                height: 45mm !important;
                margin: 0 !important;
                padding: 0 !important;
                display: block !important;
                overflow: hidden !important;
            }

            .screen-actions {
                display: none !important;
            }

            .label-card {
                width: 80mm !important;
                height: 45mm !important;
                margin: 0 !important;
                padding: 3.5mm 4mm 3mm !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                background: #ffffff !important;
                overflow: hidden !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .brand {
                font-size: 15px !important;
                margin: 0 0 1.5mm !important;
                line-height: 1.1 !important;
            }

            .product-name {
                font-size: 11px !important;
                margin: 0 0 1mm !important;
                line-height: 1.1 !important;
            }

            .info-text {
                font-size: 9px !important;
                margin: 0 0 1.8mm !important;
                line-height: 1.1 !important;
            }

            .barcode-wrap {
                width: 100% !important;
                margin: 0 0 1.5mm !important;
                overflow: hidden !important;
            }

            .barcode-box {
                width: 100% !important;
                overflow: hidden !important;
            }

            #barcode {
                width: 100% !important;
                max-width: 100% !important;
                height: 15mm !important;
                display: block !important;
            }

            .barcode-code {
                font-size: 9px !important;
                margin: 1mm 0 0.8mm !important;
                line-height: 1.1 !important;
                word-break: break-word !important;
            }

            .location-text {
                font-size: 9px !important;
                margin: 0 !important;
                line-height: 1.1 !important;
            }
        }
    </style>
</head>
<body>
    <div class="screen-page">
        <div class="screen-wrapper">
            <div class="label-card">
                <h1 class="brand">Freshbar</h1>

                <p class="product-name">
                    <?= esc($batch['product_name']) ?>
                </p>

                <p class="info-text">
                    Tanggal Masuk: <?= esc($batch['entry_date']) ?>
                </p>

                <div class="barcode-wrap">
                    <div class="barcode-box">
                        <svg id="barcode"></svg>
                    </div>
                </div>

                <p class="barcode-code">
                    <?= esc($batch['barcode']) ?>
                </p>

                <p class="location-text">
                    Lokasi: <?= esc($batch['location']) ?>
                </p>
            </div>

            <div class="screen-actions">
                <button class="btn btn-primary" onclick="printBarcode()">
                    Cetak Barcode
                </button>

                <a href="<?= site_url('stock') ?>" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

    <script>
        JsBarcode("#barcode", "<?= esc($batch['barcode']) ?>", {
            format: "CODE128",
            lineColor: "#000000",
            width: 1,
            height: 48,
            margin: 0,
            displayValue: false
        });

        function hideCodeIgniterToolbar() {
            const selectors = [
                '.ci-debug-toolbar',
                '.ci-toolbar',
                '.debug-toolbar',
                '#debugbar',
                '#debug-icon',
                '.debugbar',
                '.kint',
                '[class*="debug"]',
                '[id*="debug"]',
                'iframe[src*="debugbar"]'
            ];

            selectors.forEach(function(selector) {
                document.querySelectorAll(selector).forEach(function(element) {
                    element.style.display = 'none';
                    element.style.visibility = 'hidden';
                    element.style.opacity = '0';
                    element.style.width = '0';
                    element.style.height = '0';
                });
            });
        }

        function printBarcode() {
            hideCodeIgniterToolbar();

            const oldTitle = document.title;
            document.title = '';

            setTimeout(function() {
                window.print();
                document.title = oldTitle;
            }, 200);
        }

        window.addEventListener('load', hideCodeIgniterToolbar);
        window.addEventListener('beforeprint', hideCodeIgniterToolbar);
    </script>
</body>
</html>