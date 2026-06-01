<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <form action="<?= site_url('stock/store') ?>" method="post">
        <?= csrf_field() ?>

        <label>Nama Produk</label>
        <input type="text" name="product_name" id="product_name" list="productList" class="form-control" required autocomplete="off" placeholder="Ketik nama produk baru atau pilih yang ada" onchange="autofillProductData()">
        <datalist id="productList">
            <?php foreach ($products as $product): ?>
                <option value="<?= esc($product['name']) ?>" 
                        data-type="<?= esc($product['type']) ?>"
                        data-unit="<?= esc($product['unit']) ?>"
                        data-price="<?= esc($product['price'] ?? 0) ?>" 
                        data-shelf="<?= esc($product['shelf_life_days'] ?? 1) ?>">
                </option>
            <?php endforeach; ?>
        </datalist>

        <label>Jenis</label>
        <select name="type" id="type" class="form-control" required>
            <option value="Buah">Buah</option>
            <option value="Sayur">Sayur</option>
        </select>

        <label>Satuan</label>
        <input type="text" name="unit" id="unit" class="form-control" value="kg" required>

        <label>Tanggal Masuk</label>
        <input type="date" name="entry_date" class="form-control" value="<?= date('Y-m-d') ?>" required>

        <label>Berat / Jumlah Masuk</label>
        <input type="number" name="quantity_in" class="form-control" min="1" placeholder="Contoh: 10 (Sesuai satuan produk)" required>

        <label>Harga Jual (Rp)</label>
        <input type="number" name="price" class="form-control" min="0" placeholder="Contoh: 15000" required>

        <label>Masa Simpan (Hari)</label>
        <input type="number" name="shelf_life_days" class="form-control" min="1" placeholder="Contoh: 7" required>

        <label>Lokasi Penyimpanan</label>
        <input type="text" name="location" class="form-control" placeholder="Contoh: Rak A1 / Cold Storage">

        <label>Catatan</label>
        <textarea name="note" class="form-control"></textarea>

        <button type="submit" class="btn btn-primary">Simpan & Generate Barcode</button>
        <a href="<?= site_url('stock') ?>" class="btn btn-soft">Kembali</a>
    </form>
</div>

<script>
    function autofillProductData() {
        const inputName = document.getElementById('product_name').value;
        const datalist = document.getElementById('productList');
        const options = datalist.options;
        
        const typeSelect = document.getElementById('type');
        const unitInput = document.getElementById('unit');
        const priceInput = document.querySelector('input[name="price"]');
        const shelfInput = document.querySelector('input[name="shelf_life_days"]');
        
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === inputName) {
                // Produk sudah ada di master data, autofill detailnya
                typeSelect.value = options[i].getAttribute('data-type');
                unitInput.value = options[i].getAttribute('data-unit');
                
                const price = options[i].getAttribute('data-price');
                const shelf = options[i].getAttribute('data-shelf');
                
                if (price) priceInput.value = price;
                if (shelf) shelfInput.value = shelf;
                
                return; // Stop loop
            }
        }
        
        // Jika tidak ditemukan di datalist (produk baru), biarkan input kosong/default
    }
</script>

<?= $this->endSection() ?>