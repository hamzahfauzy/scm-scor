<?php

namespace App\Models\Scm;

use CodeIgniter\Model;

class Komposisi extends Model {

    protected $table = 'tb_produk_komposisi';
    protected $allowedFields = ['produk_id','bahan_baku_id','jumlah'];

}