<?php

namespace App\Models\Scm;

use CodeIgniter\Model;

class Stok extends Model {

    protected $table = 'tb_bahan_baku_stok';
    protected $allowedFields = ['bahan_baku_id', 'supplier_id', 'jumlah', 'tanggal','keterangan'];

}