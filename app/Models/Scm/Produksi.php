<?php

namespace App\Models\Scm;

use CodeIgniter\Model;

class Produksi extends Model {

    protected $table = 'tb_produksi';
    protected $allowedFields = ['tanggal', 'produk_id', 'jumlah_jadi', 'jumlah_gagal', 'keterangan'];

}