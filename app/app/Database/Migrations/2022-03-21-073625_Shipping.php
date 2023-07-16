<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Shipping extends Migration
{
    public function up()
    {
        $this->forge->addField([
            's_key'           => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'o_key'           => [
                'type'           => 'VARCHAR',
                'constraint'     => 200,
                'unique'         => true,
                'unsigned'       => TRUE,
                'null'           => true
            ],
            'status'           => [
                'type'           => 'varchar',
                'constraint'     => 256,
                'null'           => false
            ],
            "created_at"    => [
                'type'           => 'datetime'
            ],
            "updated_at"    => [
                'type'           => 'datetime'
            ],
            "deleted_at"    => [
                'type'           => 'datetime',
                'null'           => true
            ]
        ]);
        $this->forge->addKey('s_key', TRUE);
        $this->forge->createTable('shipping');
    }

    public function down()
    {
        //
    }
}
