<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleItemModel extends Model
{
    protected $table = 'sale_items';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'sale_id',
        'batch_id',
        'quantity',
        'price',
        'subtotal'
    ];

    protected $useTimestamps = true;

    public function getItemsBySaleId($saleId)
    {
        return $this->select('sale_items.*, products.name as product_name, stock_batches.barcode')
                    ->join('stock_batches', 'stock_batches.id = sale_items.batch_id')
                    ->join('products', 'products.id = stock_batches.product_id')
                    ->where('sale_items.sale_id', $saleId)
                    ->findAll();
    }
}
