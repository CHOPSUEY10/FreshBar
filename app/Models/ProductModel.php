<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'type', 'unit', 'shelf_life_days', 'description'];
    protected $useTimestamps = true;
}