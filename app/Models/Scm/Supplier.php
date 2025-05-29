<?php

namespace App\Models\Scm;

use CodeIgniter\Model;

class Supplier extends Model {

    protected $table = 'tb_supplier';
    protected $allowedFields = ['nama', 'alamat', 'no_hp'];

}