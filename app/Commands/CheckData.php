<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckData extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'app:checkdata';
    protected $description = 'Check Data';
    protected $usage = 'app:checkdata';
    protected $arguments = [];
    protected $options = [];

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        $products = $db->query("SELECT * FROM products")->getResultArray();
        CLI::write("Total Products: " . count($products));
        foreach ($products as $p) {
            CLI::write("- " . $p['id'] . " | " . $p['name'] . " | " . $p['price']);
        }
        
        $batches = $db->query("SELECT * FROM stock_batches")->getResultArray();
        CLI::write("\nTotal Batches: " . count($batches));
        foreach ($batches as $b) {
            CLI::write("- " . $b['id'] . " | ProdID: " . $b['product_id'] . " | Qty: " . $b['quantity_current']);
        }
        
        $orphanedBatches = $db->query("
            SELECT b.* 
            FROM stock_batches b
            LEFT JOIN products p ON b.product_id = p.id
            WHERE p.id IS NULL
        ")->getResultArray();
        CLI::write("\nOrphaned Batches (Product deleted but batch remains): " . count($orphanedBatches));
        
        $reportBatches = $db->query("
            SELECT b.*, p.name 
            FROM stock_batches b
            JOIN products p ON b.product_id = p.id
        ")->getResultArray();
        CLI::write("\nBatches in Report (Joined): " . count($reportBatches));
    }
}
