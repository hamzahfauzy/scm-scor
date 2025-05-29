<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Libraries\Page;
use App\Models\Scm\Kustomer;
use App\Models\Scm\Penjualan;
use App\Models\Scm\PenjualanProduk;
use App\Models\Scm\Produk;

class PenjualanController extends CrudController {

    protected $model = Penjualan::class;

    protected function getModel()
    {
        $model = new $this->model;
        $model->select('tb_penjualan.*, tb_kustomer.nama nama_kustomer')
            ->join('tb_kustomer', 'tb_kustomer.id = tb_penjualan.kustomer_id');

        return $model;
    }

    protected function getTitle()
    {
        return 'Penjualan';
    }

    protected function getSlug()
    {
        return 'penjualan';
    }

    protected function columns()
    {
        return [
            'tanggal' => [
                'label' => 'Tanggal'
            ],
            'nama_kustomer' => [
                'label' => 'Kustomer'
            ],
            'jumlah_produk' => [
                'label' => 'Jumlah Produk'
            ],
            'total_produk' => [
                'label' => 'Total Produk'
            ],
        ];
    }

    protected function fields()
    {
        $kustomer = (new Kustomer)->findAll();
        $options = [0 => 'Pilih Kustomer'];
        foreach($kustomer as $item)
        {
            $options[$item['id']] = $item['nama'];
        }
        return [
            'tanggal' => [
                'label' => 'Tanggal',
                'type' => 'date',
                'default_value' => date('Y-m-d')
            ],
            'kustomer_id' => [
                'label' => 'Kustomer',
                'type' => 'select',
                'options' => $options
            ],
        ];
    }

    public function create()
    {
        $page = new Page;
        $page->setTitle('Tambah ' . $this->getTitle());
        $page->setSlug($this->getSlug());
        $page->setBreadcrumbs([
            [
                'label' => $this->getTitle(),
                'url' => '/'.$this->getSlug()
            ],
            [
                'label' => 'Tambah Data',
                'url' => false
            ],
        ]);

        $produk = (new Produk)->findAll();

        return $page->render('scm/form', [
            'data' => [],
            'fields' => $this->fields(),
            'columns' => $this->columns(),
            'produk' => $produk
        ]);
    }

    protected function beforeInsert($request)
    {
        $request['total_produk'] = array_sum(array_column($request['items'], 'jumlah'));
        $request['jumlah_produk'] = count($request['items']);

        return $request;
    }

    protected function afterInsert($request, $data)
    {
        foreach($request['items'] as $item)
        {
            $item['penjualan_id'] = $data;
            (new PenjualanProduk)->insert($item);
        }
    }

    protected function detailButton($data)
    {
        return '<a href="/penjualan-produk?filter[penjualan_id]='.$data['id'].'" class="btn btn-sm btn-info">Detail</a>';
    }

}