<?php

namespace App\Models\Scm;

use CodeIgniter\Model;

class Retur extends Model {

    protected $table = 'tb_retur_penjualan';
    protected $allowedFields = ['penjualan_id', 'total_produk', 'jumlah_produk', 'tanggal', 'alasan'];

}