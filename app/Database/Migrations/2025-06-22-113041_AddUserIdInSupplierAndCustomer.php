<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdInSupplierAndCustomer extends Migration
{
    public function up()
    {
        //
        $fields = array(
            'user_id' => array(
                'type' => 'int',
                'constraint' => 100
            )
        );
        $this->forge->addColumn('tb_supplier', $fields);
        $this->forge->addColumn('tb_kustomer', $fields);
    }

    public function down()
    {
        //
        $this->forge->dropColumn('tb_supplier', 'user_id');
        $this->forge->dropColumn('tb_kustomer', 'user_id');
    }
}
