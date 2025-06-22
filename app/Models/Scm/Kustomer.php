<?php

namespace App\Models\Scm;

use CodeIgniter\Model;

class Kustomer extends Model {

    protected $table = 'tb_kustomer';
    protected $allowedFields = ['nama', 'alamat', 'no_hp','user_id'];

}