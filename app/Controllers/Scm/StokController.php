<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Libraries\Page;
use App\Models\Scm\BahanBaku;
use App\Models\Scm\BahanBakuSupplier;
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
        $model = (new $this->model)
            ->select('tb_bahan_baku_stok.*, tb_bahan_baku.nama nama_bahan, tb_supplier.nama nama_supplier')
            // ->where('bahan_baku_id', $bahanBaku)
            ->join('tb_bahan_baku', 'tb_bahan_baku.id = tb_bahan_baku_stok.bahan_baku_id')
            ->join('tb_supplier', 'tb_supplier.id = tb_bahan_baku_stok.supplier_id', 'LEFT')
            ;

        if(session()->get('level') == 'Admin')
        {
            $bahanBaku = $_GET['filter']['bahan_baku_id'];
            $model->where('bahan_baku_id', $bahanBaku);
        }
        else
        {
            $model->where('tb_supplier.user_id', session()->get('id'))->where('tb_bahan_baku_stok.status', 'REQUEST');
        }

        return $model;
    }

    public function index()
    {
        $data = $this->getModel()->findAll();
        
        $page = new Page;
        $page->setTitle('Data ' . $this->getTitle());
        $page->setSlug($this->getSlug());
        $view = 'crud/index';

        if(session()->get('level') == 'Admin')
        {
            $bahanBaku = (new BahanBaku)->where('id', $_GET['filter']['bahan_baku_id'])->first();
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
        }
        else
        {
            $page->setBreadcrumbs([
                [
                    'label' => 'Bahan Baku',
                    'url' => '/stok'
                ],
            ]);

            $view = 'scm/stok';
        }

        return $page->render($view, [
            'data' => $data,
            'detail_button' => function($data){
                return $data['status'] == 'REQUEST' ? '<a href="/stok/confirm/'.$data['id'].'" class="btn btn-sm btn-success" onclick="return confirm(\'Apakah anda yakin konfirmasi permintaan ini ?\')">Konfirmasi</a>' : '';
            },
            'canAdd' => $this->canAdd,
            'canEdit' => $this->canEdit,
            'canDelete' => $this->canDelete,
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
            'status' => [
                'label' => 'Status'
            ]
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

    protected function beforeInsert($data)
    {
        $data['status'] = 'REQUEST';

        return $data;
    }

    function confirm($id)
    {
        $stok = (new Stok)->find($id);
        $supplier = (new Supplier)->find($stok['supplier_id']);
        $bahanBaku = (new BahanBakuSupplier)->where('bahan_baku_id', $stok['bahan_baku_id'])->where('supplier_id', $supplier['user_id'])->first();

        (new BahanBakuSupplier)->update($bahanBaku['id'], [
            'stok' => $bahanBaku['stok'] - $stok['jumlah']
        ]);

        (new Stok)->update($id, [
            'status' => 'CONFIRM'
        ]);

        return redirect()->to('/'. $this->getSlug() . (isset($_GET['filter']) ? '?'.http_build_query($_GET) : ''));
    }

}