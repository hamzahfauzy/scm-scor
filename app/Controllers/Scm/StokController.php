<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Libraries\Page;
use App\Models\Scm\BahanBaku;
use App\Models\Scm\Stok;
use App\Models\Scm\Supplier;

class StokController extends CrudController {

    protected $model = Stok::class;

    protected function getTitle()
    {
        return 'Stok';
    }

    protected function getSlug()
    {
        return 'stok';
    }

    protected function getModel()
    {
        $bahanBaku = $_GET['filter']['bahan_baku_id'];
        $model = (new $this->model)
            ->select('tb_bahan_baku_stok.*, tb_bahan_baku.nama nama_bahan, tb_supplier.nama nama_supplier')
            ->where('bahan_baku_id', $bahanBaku)
            ->join('tb_bahan_baku', 'tb_bahan_baku.id = tb_bahan_baku_stok.bahan_baku_id')
            ->join('tb_supplier', 'tb_supplier.id = tb_bahan_baku_stok.supplier_id', 'LEFT')
            ;

        return $model;
    }

    public function index()
    {
        $data = $this->getModel()->findAll();
        $bahanBaku = (new BahanBaku)->where('id', $_GET['filter']['bahan_baku_id'])->first();

        $page = new Page;
        $page->setTitle('Data ' . $this->getTitle());
        $page->setSlug($this->getSlug());
        $page->setBreadcrumbs([
            [
                'label' => 'Bahan Baku',
                'url' => '/bahan-baku'
            ],
            [
                'label' => $bahanBaku['nama'],
                'url' => false
            ],
            [
                'label' => 'Stok',
                'url' => false
            ],
        ]);

        return $page->render('crud/index', [
            'data' => $data,
            'detail_button' => function($data){
                return $this->detailButton($data);
            },
            'columns' => $this->columns()
        ]);
    }

    protected function columns()
    {
        return [
            'tanggal' => [
                'label' => 'Tanggal'
            ],
            'nama_bahan' => [
                'label' => 'Bahan Baku'
            ],
            'nama_supplier' => [
                'label' => 'Nama Suplier'
            ],
            'jumlah' => [
                'label' => 'Jumlah'
            ],
            'keterangan' => [
                'label' => 'Keterangan'
            ],
        ];
    }

    protected function details()
    {
        return [];
    }

    protected function fields()
    {
        $supplier = (new Supplier)->findAll();
        $bahanBaku = (new BahanBaku)->findAll();
        $optionSupplier = [0 => 'Pilih Supplier'];
        $optionBahanBaku = [0 => 'Pilih Bahan Baku'];
        foreach($supplier as $item)
        {
            $optionSupplier[$item['id']] = $item['nama'];
        }
        
        foreach($bahanBaku as $item)
        {
            $optionBahanBaku[$item['id']] = $item['nama'];
        }

        return [
            'tanggal' => [
                'label' => 'Tanggal Terima',
                'type' => 'date',
                'default_value' => date('Y-m-d')
            ],
            'tanggal_pesan' => [
                'label' => 'Tanggal Pesan',
                'type' => 'date',
                'default_value' => date('Y-m-d')
            ],
            'supplier_id' => [
                'label' => 'Supplier',
                'type' => 'select',
                'options' => $optionSupplier
            ],
            'bahan_baku_id' => [
                'label' => 'Bahan Baku',
                'type' => 'select',
                'options' => $optionBahanBaku
            ],
            'jumlah' => [
                'label' => 'Jumlah',
                'type' => 'number',
            ],
            'keterangan' => [
                'label' => 'Keterangan',
                'type' => 'textarea',
            ],
        ];
    }

}