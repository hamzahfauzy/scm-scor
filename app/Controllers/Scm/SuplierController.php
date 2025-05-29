<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Models\Scm\Supplier;

class SuplierController extends CrudController {

    protected $model = Supplier::class;

    protected function getTitle()
    {
        return 'Suplier';
    }

    protected function getSlug()
    {
        return 'suplier';
    }

    protected function columns()
    {
        return [
            'nama' => [
                'label' => 'Nama'
            ],
            'no_hp' => [
                'label' => 'No. HP'
            ],
            'alamat' => [
                'label' => 'Alamat'
            ],
        ];
    }

    protected function details()
    {
        return [];
    }

    protected function fields()
    {
        return [
            'nama' => [
                'label' => 'Nama',
                'type' => 'text',
            ],
            'no_hp' => [
                'label' => 'No. HP',
                'type' => 'text',
            ],
            'alamat' => [
                'label' => 'Alamat',
                'type' => 'textarea',
            ],
        ];
    }

}