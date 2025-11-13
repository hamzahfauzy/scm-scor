<?php

namespace App\Models\Scm;

use CodeIgniter\Model;

class BahanBaku extends Model {

    protected $table = 'tb_bahan_baku';
    protected $allowedFields = ['nama', 'satuan', 'stok_minimum','supplier_id','stok_supplier','harga'];

}