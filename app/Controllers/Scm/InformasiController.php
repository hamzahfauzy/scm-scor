<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Models\Scm\Informasi;

class InformasiController extends CrudController {

    protected $model = Informasi::class;
    protected $canAdd = false;
    protected $canEdit = false;
    protected $canDelete = false;

    protected function getTitle()
    {
        return 'Informasi';
    }

    protected function getSlug()
    {
        return 'informasi';
    }

    protected function columns()
    {
        return [
            'keterangan' => [
                'label' => 'Keterangan'
            ],
            'tanggal' => [
                'label' => 'Tanggal'
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
        return '<a href="/informasi/pesan/'.$data['id'].'/'.$data['bahan_baku_id'].'" class="btn btn-sm btn-info">Pesan</a>';
    }

    protected function getModel()
    {
        return (new Informasi)->where('status',null);
    }

    public function pesan($id, $bahan_baku_id)
    {
        (new Informasi)->update($id, [
            'status' => 1
        ]);

        return redirect()->to('/stok/create?filter[bahan_baku_id]='.$bahan_baku_id);
    }

}