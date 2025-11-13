<?php

namespace App\Models\Scm;

use CodeIgniter\Model;

class Informasi extends Model {

    protected $table = 'tb_informasi';
    protected $allowedFields = ['bahan_baku_id', 'keterangan','status'];

}