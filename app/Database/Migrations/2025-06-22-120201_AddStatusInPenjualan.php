<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusInPenjualan extends Migration
{
    public function up()
    {
        //
        $fields = array(
            'status' => array(
                'type' => 'varchar',
                'constraint' => 100,
                'default' => 'CONFIRM'
            )
        );
        $this->forge->addColumn('tb_penjualan', $fields);
        $this->forge->addColumn('tb_bahan_baku_stok', $fields);
    }

    public function down()
    {
        //
        $this->forge->dropColumn('tb_penjualan', 'status');
        $this->forge->dropColumn('tb_bahan_baku_stok', 'status');
    }
}
