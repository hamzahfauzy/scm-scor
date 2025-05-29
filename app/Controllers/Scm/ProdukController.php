<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Models\Scm\Produk;

class ProdukController extends CrudController {

    protected $model = Produk::class;

    protected function getTitle()
    {
        return 'Produk';
    }

    protected function getSlug()
    {
        return 'produk';
    }

    protected function columns()
    {
        return [
            'nama' => [
                'label' => 'Nama'
            ],
        ];
    }

    protected function fields()
    {
        return [
            'nama' => [
                'label' => 'Nama',
                'type' => 'text',
            ],
        ];
    }

    protected function detailButton($data)
    {
        return '<a href="/komposisi?filter[produk_id]='.$data['id'].'" class="btn btn-sm btn-info">Komposisi</a>';
    }

}