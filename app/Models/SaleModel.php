<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'invoice_no',
        'user_id',
        'total_items',
        'total_amount'
    ];

    protected $useTimestamps = true;

    public function getSalesWithUser()
    {
        return $this->select('sales.*, users.name as cashier_name')
                    ->join('users', 'users.id = sales.user_id')
                    ->orderBy('sales.created_at', 'DESC')
                    ->findAll();
    }
}
