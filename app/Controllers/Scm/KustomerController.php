<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Models\Scm\Kustomer;

class KustomerController extends CrudController {

    protected $model = Kustomer::class;

    protected function getTitle()
    {
        return 'Kustomer';
    }

    protected function getSlug()
    {
        return 'kustomer';
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