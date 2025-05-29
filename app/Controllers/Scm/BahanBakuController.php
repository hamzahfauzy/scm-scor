<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Models\Scm\BahanBaku;

class BahanBakuController extends CrudController {

    protected $model = BahanBaku::class;

    protected function getTitle()
    {
        return 'Bahan Baku';
    }

    protected function getSlug()
    {
        return 'bahan-baku';
    }

    protected function getModel()
    {
        $model = new $this->model;
        $model->select('tb_bahan_baku.*, COALESCE((SELECT SUM(jumlah) FROM tb_bahan_baku_stok WHERE bahan_baku_id = tb_bahan_baku.id), 0) total_stok');

        return $model;
    }

    protected function columns()
    {
        return [
            'nama' => [
                'label' => 'Nama'
            ],
            'satuan' => [
                'label' => 'Satuan'
            ],
            'total_stok' => [
                'label' => 'Stok'
            ],
            'stok_minimum' => [
                'label' => 'Stok Minimum'
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
            'satuan' => [
                'label' => 'Satuan',
                'type' => 'text',
            ],
            'stok_minimum' => [
                'label' => 'Stok Minimum',
                'type' => 'number',
            ],
        ];
    }

    protected function detailButton($data)
    {
        return '<a href="/stok?filter[bahan_baku_id]='.$data['id'].'" class="btn btn-sm btn-info">Stok</a>';
    }

}