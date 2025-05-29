<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitScmTable extends Migration
{
    public function up()
    {
        // tb_supplier
        $this->forge->addField([
            'id'   => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 100],
            'alamat' => ['type' => 'TEXT', 'constraint' => 100],
            'no_hp' => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_supplier');

        // tb_bahan_baku
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'satuan'        => ['type' => 'VARCHAR', 'constraint' => 50],
            'stok_minimum'  => ['type' => 'INT', 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_bahan_baku');

        // tb_bahan_baku_stok
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'bahan_baku_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'supplier_id'   => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'jumlah'        => ['type' => 'FLOAT'],
            'tanggal'       => ['type' => 'DATE'],
            'keterangan'    => ['type' => 'TEXT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('bahan_baku_id', 'tb_bahan_baku', 'id', '', 'CASCADE', 'fk_tb_bahan_baku_stok_bahan_baku_id');
        $this->forge->addForeignKey('supplier_id', 'tb_supplier', 'id', '', 'RESTRICT', 'fk_tb_bahan_baku_stok_supplier_id');
        $this->forge->createTable('tb_bahan_baku_stok');

        // tb_produk
        $this->forge->addField([
            'id'   => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_produk');

        // tb_produk_komposisi
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'produk_id'     => ['type' => 'BIGINT', 'unsigned' => true],
            'bahan_baku_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'jumlah'        => ['type' => 'FLOAT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('produk_id', 'tb_produk', 'id', '', 'CASCADE', 'fk_tb_produk_komposisi_produk_id');
        $this->forge->addForeignKey('bahan_baku_id', 'tb_bahan_baku', 'id', '', 'RESTRICT', 'fk_tb_produk_komposisi_bahan_baku_id');
        $this->forge->createTable('tb_produk_komposisi');

        // tb_kustomer
        $this->forge->addField([
            'id'     => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'no_hp'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'alamat' => ['type' => 'TEXT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_kustomer');

        // tb_penjualan
        $this->forge->addField([
            'id'             => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'kustomer_id'    => ['type' => 'BIGINT', 'unsigned' => true],
            'total_produk'   => ['type' => 'FLOAT'],
            'jumlah_produk'  => ['type' => 'INT'],
            'tanggal'        => ['type' => 'DATE'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kustomer_id', 'tb_kustomer', 'id', '', 'RESTRICT', 'fk_tb_penjualan_kustomer_id');
        $this->forge->createTable('tb_penjualan');

        // tb_penjualan_produk
        $this->forge->addField([
            'id'           => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'penjualan_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'produk_id'    => ['type' => 'BIGINT', 'unsigned' => true],
            'jumlah'       => ['type' => 'FLOAT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('penjualan_id', 'tb_penjualan', 'id', '', 'CASCADE', 'fk_tb_penjualan_produk_penjualan_id');
        $this->forge->addForeignKey('produk_id', 'tb_produk', 'id', '', 'RESTRICT', 'fk_tb_produk_penjualan_produk_id');
        $this->forge->createTable('tb_penjualan_produk');

        // tb_produksi
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tanggal'       => ['type' => 'DATE'],
            'produk_id'     => ['type' => 'BIGINT', 'unsigned' => true],
            'jumlah_jadi'   => ['type' => 'FLOAT'],
            'jumlah_gagal'  => ['type' => 'FLOAT'],
            'keterangan'    => ['type' => 'TEXT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('produk_id', 'tb_produk', 'id', '', 'CASCADE', 'fk_tb_produksi_produk_produk_id');
        $this->forge->createTable('tb_produksi');

        // tb_pengiriman
        $this->forge->addField([
            'id'          => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'penjualan_id'=> ['type' => 'BIGINT', 'unsigned' => true],
            'status'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'pengantar'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'keterangan'  => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_pengiriman');

        // tb_retur_penjualan
        $this->forge->addField([
            'id'             => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'penjualan_id'   => ['type' => 'BIGINT', 'unsigned' => true],
            'tanggal'        => ['type' => 'DATE'],
            'total_produk'   => ['type' => 'FLOAT'],
            'jumlah_produk'  => ['type' => 'INT'],
            'alasan'         => ['type' => 'TEXT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('penjualan_id', 'tb_penjualan', 'id', '', 'RESTRICT', 'fk_retur_penjualan_penjualan_id');
        $this->forge->createTable('tb_retur_penjualan');

        // tb_retur_produk
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'retur_id'   => ['type' => 'BIGINT', 'unsigned' => true],
            'produk_id'  => ['type' => 'BIGINT', 'unsigned' => true],
            'jumlah'     => ['type' => 'FLOAT'],
            'keterangan' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('retur_id', 'tb_retur_penjualan', 'id', '', 'CASCADE', 'fk_tb_retur_produk_retur_id');
        $this->forge->addForeignKey('produk_id', 'tb_produk', 'id', '', 'RESTRICT', 'fk_tb_retur_produk_id');
        $this->forge->createTable('tb_retur_produk');
    }

    public function down()
    {
        $this->forge->dropTable('tb_retur_produk');
        $this->forge->dropTable('tb_retur_penjualan');
        $this->forge->dropTable('tb_pengiriman');
        $this->forge->dropTable('tb_produksi_produk');
        $this->forge->dropTable('tb_produksi');
        $this->forge->dropTable('tb_penjualan_produk');
        $this->forge->dropTable('tb_penjualan');
        $this->forge->dropTable('tb_kustomer');
        $this->forge->dropTable('tb_produk_komposisi');
        $this->forge->dropTable('tb_produk');
        $this->forge->dropTable('tb_bahan_baku_stok');
        $this->forge->dropTable('tb_bahan_baku');
        $this->forge->dropTable('tb_supplier');
    }
}
