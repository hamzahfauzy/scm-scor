<?php

namespace App\Models\Scm;

use CodeIgniter\Model;

class ReturProduk extends Model {

    protected $table = 'tb_retur_produk';
    protected $allowedFields = ['retur_id', 'produk_id', 'jumlah'];

}