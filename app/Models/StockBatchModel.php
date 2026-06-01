<?php

namespace App\Models;

use CodeIgniter\Model;

class StockBatchModel extends Model
{
    protected $table = 'stock_batches';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'product_id',
        'entry_date',
        'quantity_in',
        'quantity_current',
        'freshness_status',
        'location',
        'note',
        'created_by',
    ];

    protected $useTimestamps = true;

    public function getWithProducts($id = null)
    {
        $this->select('
                stock_batches.*,
                products.name AS product_name,
                products.type,
                products.unit,
                products.price,
                products.barcode,
                products.shelf_life_days,
                users.name AS created_by_name
            ')
            ->join('products', 'products.id = stock_batches.product_id')
            ->join('users', 'users.id = stock_batches.created_by', 'left')
            ->orderBy('stock_batches.id', 'DESC');

        if ($id !== null) {
            return $this->where('stock_batches.id', $id)->first();
        }

        return $this->findAll();
    }

    /**
     * Dapatkan stok tertua yang masih tersedia berdasarkan barcode produk (FIFO)
     */
    public function getOldestAvailableBatchByBarcode($barcode)
    {
        return $this->select('
                stock_batches.*,
                products.name AS product_name,
                products.type,
                products.unit,
                products.price,
                products.barcode,
                products.shelf_life_days
            ')
            ->join('products', 'products.id = stock_batches.product_id')
            ->where('products.barcode', $barcode)
            ->where('stock_batches.quantity_current >', 0)
            ->orderBy('stock_batches.entry_date', 'ASC') // FIFO: cari yang paling lama dulu
            ->first();
    }
}