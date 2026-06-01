<?php

namespace App\Models;

use CodeIgniter\Model;

class StockBatchModel extends Model
{
    protected $table = 'stock_batches';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'product_id',
        'barcode',
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

    public function findByBarcode($barcode)
    {
        return $this->select('
                stock_batches.*,
                products.name AS product_name,
                products.type,
                products.unit,
                products.shelf_life_days
            ')
            ->join('products', 'products.id = stock_batches.product_id')
            ->where('stock_batches.barcode', $barcode)
            ->first();
    }
}