<?php

namespace App\Models\Scm;

use CodeIgniter\Model;

class PenjualanProduk extends Model {

    protected $table = 'tb_penjualan_produk';
    protected $allowedFields = ['penjualan_id', 'produk_id', 'jumlah'];

}