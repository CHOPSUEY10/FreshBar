<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DbCheck extends BaseCommand
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
    protected $name = 'app:dbcheck';
    protected $description = 'Inspect products table';
    protected $usage = 'app:dbcheck';
    protected $arguments = [];
    protected $options = [];

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        CLI::write("Fields in products table:");
        $fields = $db->getFieldData('products');
        foreach ($fields as $field) {
            CLI::write("- {$field->name}: type={$field->type}, max_len={$field->max_length}, nullable=" . ($field->nullable ? 'YES' : 'NO'));
        }
    }
}
