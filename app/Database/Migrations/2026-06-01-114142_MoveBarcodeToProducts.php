<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MoveBarcodeToProducts extends Migration
{
    public function up()
    {
        // 1. Tambah barcode ke products
        $this->forge->addColumn('products', [
            'barcode' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'unique'     => true,
                'after'      => 'unit'
            ]
        ]);

        // 2. Hapus barcode dari stock_batches
        $this->forge->dropColumn('stock_batches', 'barcode');
    }

    public function down()
    {
        // Rollback
        $this->forge->addColumn('stock_batches', [
            'barcode' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ]
        ]);

        $this->forge->dropColumn('products', 'barcode');
    }
}
