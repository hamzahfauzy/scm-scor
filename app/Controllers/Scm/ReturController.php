<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Libraries\Page;
use App\Models\Scm\Penjualan;
use App\Models\Scm\Produk;
use App\Models\Scm\Retur;
use App\Models\Scm\ReturProduk;

class ReturController extends CrudController {

    protected $model = Retur::class;

    protected function getModel()
    {
        $model = new $this->model;
        $model->select('tb_retur_penjualan.*, CONCAT(tb_penjualan.tanggal," - ",tb_kustomer.nama) nama_kustomer')
            ->join('tb_penjualan', 'tb_penjualan.id = tb_retur_penjualan.penjualan_id')
            ->join('tb_kustomer', 'tb_kustomer.id = tb_penjualan.kustomer_id');

        return $model;
    }

    protected function getTitle()
    {
        return 'Retur Penjualan';
    }

    protected function getSlug()
    {
        return 'retur';
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
        $kustomer = (new Penjualan)->select('tb_penjualan.*, CONCAT(tb_penjualan.tanggal," - ",tb_kustomer.nama) nama_kustomer')
            ->where('tb_penjualan.id NOT IN (SELECT penjualan_id FROM tb_retur_penjualan)')
            ->join('tb_kustomer', 'tb_kustomer.id = tb_penjualan.kustomer_id')
            ->findAll();
        $options = [0 => 'Pilih Penjualan'];
        foreach($kustomer as $item)
        {
            $options[$item['id']] = $item['nama_kustomer'];
        }
        return [
            'tanggal' => [
                'label' => 'Tanggal',
                'type' => 'date',
                'default_value' => date('Y-m-d')
            ],
            'penjualan_id' => [
                'label' => 'Penjualan',
                'type' => 'select',
                'options' => $options
            ],
            'alasan' => [
                'label' => 'Alasan',
                'type' => 'textarea'
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

        return $page->render('scm/retur-form', [
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
            $item['retur_id'] = $data;
            (new ReturProduk())->insert($item);
        }
    }

    protected function detailButton($data)
    {
        return '<a href="/retur-produk?filter[retur_id]='.$data['id'].'" class="btn btn-sm btn-info">Detail</a>';
    }

}