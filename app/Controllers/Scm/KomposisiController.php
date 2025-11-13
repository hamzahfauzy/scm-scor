<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Libraries\Page;
use App\Models\Scm\BahanBaku;
use App\Models\Scm\Komposisi;
use App\Models\Scm\Produk;

class KomposisiController extends CrudController {

    protected $model = Komposisi::class;

    protected function getTitle()
    {
        return 'Komposisi';
    }

    protected function getSlug()
    {
        return 'komposisi';
    }

    public function index()
    {
        $data = $this->getModel()->findAll();
        $produk = (new Produk)->where('id', $_GET['filter']['produk_id'])->first();

        $page = new Page;
        $page->setTitle('Data ' . $this->getTitle());
        $page->setSlug($this->getSlug());
        $page->setBreadcrumbs([
            [
                'label' => 'Produk',
                'url' => '/produk'
            ],
            [
                'label' => $produk['nama'],
                'url' => false
            ],
            [
                'label' => 'Komposisi',
                'url' => false
            ],
        ]);

        return $page->render('crud/index', [
            'data' => $data,
            'detail_button' => function($data){
                return $this->detailButton($data);
            },
            'canAdd' => $this->canAdd,
            'canEdit' => $this->canEdit,
            'canDelete' => $this->canDelete,
            'columns' => $this->columns()
        ]);
    }

    protected function getModel()
    {
        $model = new $this->model;
        $model->select('tb_produk_komposisi.*, tb_produk.nama nama_produk, tb_bahan_baku.nama nama_bahan_baku, CONCAT(jumlah, " ", tb_bahan_baku.satuan) jumlah_satuan')
            ->where('produk_id', $_GET['filter']['produk_id'])
            ->join('tb_produk', 'tb_produk.id = tb_produk_komposisi.produk_id')
            ->join('tb_bahan_baku', 'tb_bahan_baku.id = tb_produk_komposisi.bahan_baku_id')
            ;

        return $model;
    }

    protected function columns()
    {
        return [
            'nama_produk' => [
                'label' => 'Produk'
            ],
            'nama_bahan_baku' => [
                'label' => 'Bahan Baku'
            ],
            'jumlah_satuan' => [
                'label' => 'Jumlah'
            ],
        ];
    }

    protected function fields()
    {
        $options = [0 => 'Pilih Bahan Baku'];
        $bahanBaku = (new BahanBaku)->findAll();
        foreach($bahanBaku as $bahan)
        {
            $options[$bahan['id']] = $bahan['nama'];
        }

        $produk = (new Produk)->where('id', $_GET['filter']['produk_id'])->first();
        return [
            'produk_id' => [
                'label' => 'Produk',
                'type' => 'text',
                'value' => $produk['id'] .' - '. $produk['nama'],
                'readonly' => 'readonly'
            ],
            'bahan_baku_id' => [
                'label' => 'Bahan Baku',
                'type' => 'select',
                'options' => $options,
            ],
            'jumlah' => [
                'label' => 'Jumlah',
                'type' => 'number',
            ],
        ];
    }

    protected function beforeInsert($data)
    {
        $data['produk_id'] = $_GET['filter']['produk_id'];

        return $data;
    }
    
    protected function beforeUpdate($data)
    {
        $data['produk_id'] = $_GET['filter']['produk_id'];

        return $data;
    }

}