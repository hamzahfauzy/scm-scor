<?php

namespace App\Models\Scm;

use CodeIgniter\Model;

class Penjualan extends Model {

    protected $table = 'tb_penjualan';
    protected $allowedFields = ['kustomer_id', 'total_produk', 'jumlah_produk', 'tanggal'];

}