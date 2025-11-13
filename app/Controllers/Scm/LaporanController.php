<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Models\Scm\Stok;
use App\Libraries\Page;

class LaporanController extends CrudController {

    protected $model = Stok::class;
    protected $canAdd = false;
    protected $canEdit = false;
    protected $canDelete = false;

    protected function getTitle()
    {
        return 'Laporan';
    }

    protected function getSlug()
    {
        return 'laporan';
    }

    protected function getFilter()
    {
        return isset($_GET['filter']) ? $_GET['filter'] : [
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d'),
        ];
    }

    protected function getModel()
    {
        $model = (new $this->model)
            ->select('tb_bahan_baku_stok.*, tb_bahan_baku.nama nama_bahan, tb_supplier.nama nama_supplier, tb_bahan_baku.harga, (coalesce(tb_bahan_baku.harga,0)*tb_bahan_baku_stok.jumlah) sub_total_harga')
            // ->where('bahan_baku_id', $bahanBaku)
            ->join('tb_bahan_baku', 'tb_bahan_baku.id = tb_bahan_baku_stok.bahan_baku_id')
            ->join('tb_supplier', 'tb_supplier.id = tb_bahan_baku_stok.supplier_id', 'LEFT')
            ->where('tb_bahan_baku_stok.status', 'CONFIRM')
            ->where('tb_supplier.nama IS NOT NULL', NULL)
            ;

        if(session()->get('level') == 'Supplier')
        {
            $model->where('tb_supplier.user_id', session()->get('id'));
        }

        $filter = $this->getFilter();

        $model->where('(tb_bahan_baku_stok.tanggal BETWEEN "'.$filter['start_date'].' 00:00:00" AND "'.$filter['end_date'].' 23:59:59")');

        return $model;
    }

    public function index()
    {
        $data = $this->getModel()->findAll();
        
        $page = new Page;
        $page->setTitle('Laporan');
        $page->setSlug($this->getSlug());
        $view = 'scm/laporan';
        $page->setBreadcrumbs([
            [
                'label' => 'Laporan',
                'url' => '/'
            ],
        ]);

        return $page->render($view, [
            'data' => $data,
            'detail_button' => '',
            'filter' => $this->getFilter(),
            'canAdd' => false,
            'canEdit' => false,
            'canDelete' => false,
            'columns' => $this->columns()
        ]);
    }

    public function cetak()
    {
        $data = $this->getModel()->findAll();

        $title = session()->get('level') == 'Supplier' ? 'Penjualan' : 'Pembelian';

        return view('scm/cetak-laporan', [
            'data' => $data,
            'title' => $title,
            'columns' => $this->columns(),
            'filter' => $this->getFilter(),
        ]);
    }

    protected function columns()
    {
        $columns = [
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
            'harga' => [
                'label' => 'Harga'
            ],
            'sub_total_harga' => [
                'label' => 'Total'
            ],
            // 'keterangan' => [
            //     'label' => 'Keterangan'
            // ],
            // 'status' => [
            //     'label' => 'Status'
            // ]
        ];

        if(session()->get('level') == 'Supplier')
        {
            unset($columns['nama_supplier']);
        }

        return $columns;
    }

    protected function details()
    {
        return [];
    }

}