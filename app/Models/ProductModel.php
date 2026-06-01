<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'type', 'unit', 'price', 'barcode', 'shelf_life_days', 'description'];
    protected $useTimestamps = true;

    /**
     * Mengambil data produk beserta total stok dan lokasi dari tabel stock_batches
     */
    public function getWithStock()
    {
        return $this->select('products.*, COALESCE(SUM(stock_batches.quantity_current), 0) as total_stock, GROUP_CONCAT(DISTINCT stock_batches.location SEPARATOR ", ") as locations')
                    ->join('stock_batches', 'stock_batches.product_id = products.id', 'left')
                    ->groupBy('products.id')
                    ->orderBy('products.id', 'DESC')
                    ->findAll();
    }
}