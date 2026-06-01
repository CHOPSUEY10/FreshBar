<?php

namespace App\Models;

use CodeIgniter\Model;

class ScanLogModel extends Model
{
    protected $table = 'scan_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'batch_id',
        'user_id',
        'freshness_status',
        'scanned_at',
    ];

    protected $useTimestamps = true;
}