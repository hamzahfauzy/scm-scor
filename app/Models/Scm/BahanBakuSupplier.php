<?php

namespace App\Models\Scm;

use CodeIgniter\Model;

class BahanBakuSupplier extends Model {

    protected $table = 'tb_bahan_baku_supplier';
    protected $allowedFields = ['bahan_baku_id', 'supplier_id', 'stok'];

}