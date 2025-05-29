<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Libraries\Page;
use App\Models\Scm\Penjualan;
use App\Models\Scm\PenjualanProduk;
use App\Models\Scm\Produk;

class PenjualanProdukController extends CrudController {

    protected $model = PenjualanProduk::class;

    protected function getTitle()
    {
        return 'Detail Penjualan';
    }

    protected function getSlug()
    {
        return 'penjualan-produk';
    }

    public function index()
    {
        $data = $this->getModel()->findAll();
        $penjualan = (new Penjualan())
                        ->select('tb_penjualan.*, tb_kustomer.nama nama_kustomer')
                        ->where('tb_penjualan.id', $_GET['filter']['penjualan_id'])
                        ->join('tb_kustomer', 'tb_kustomer.id = tb_penjualan.kustomer_id')
                        ->first();

        $page = new Page;
        $page->setTitle('Data ' . $this->getTitle());
        $page->setSlug($this->getSlug());
        $page->setBreadcrumbs([
            [
                'label' => 'Penjualan',
                'url' => '/penjualan'
            ],
            [
                'label' => $penjualan['id'] .' - '. $penjualan['nama_kustomer'],
                'url' => false
            ],
            [
                'label' => 'Detail',
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

    protected function getModel()
    {
        $model = new $this->model;
        $model->select('tb_penjualan_produk.*, tb_kustomer.nama nama_kustomer, tb_produk.nama nama_produk')
            ->where('penjualan_id', $_GET['filter']['penjualan_id'])
            ->join('tb_penjualan', 'tb_penjualan.id = tb_penjualan_produk.penjualan_id')
            ->join('tb_kustomer', 'tb_kustomer.id = tb_penjualan.kustomer_id')
            ->join('tb_produk', 'tb_produk.id = tb_penjualan_produk.produk_id')
            ;

        return $model;
    }

    protected function columns()
    {
        return [
            'nama_kustomer' => [
                'label' => 'Kustomer'
            ],
            'nama_produk' => [
                'label' => 'Produk'
            ],
            'jumlah' => [
                'label' => 'Jumlah'
            ],
        ];
    }

    protected function fields()
    {
        $options = [0 => 'Pilih Produk'];
        $penjualan_id = $_GET['filter']['penjualan_id'];
        $produk_id = $this->record ? $this->record['produk_id'] : 0;
        $produk = (new Produk)->where('id NOT IN (SELECT produk_id FROM tb_penjualan_produk WHERE penjualan_id = '.$penjualan_id.' AND produk_id <> '.$produk_id.')')->findAll();
        foreach($produk as $p)
        {
            $options[$p['id']] = $p['nama'];
        }

        return [
            'produk_id' => [
                'label' => 'Produk',
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
        $data['penjualan_id'] = $_GET['filter']['penjualan_id'];

        return $data;
    }
    
    protected function beforeUpdate($data)
    {
        $data['penjualan_id'] = $_GET['filter']['penjualan_id'];

        return $data;
    }

    protected function afterInsert($request, $data)
    {
        $penjualanProduk = (new $this->model)->where('id', $data)->first();
        $penjualan_id = $penjualanProduk['penjualan_id'];
        $penjualanProduk = (new $this->model)->where('penjualan_id', $penjualan_id)->findAll();

        $update = [
            'jumlah_produk' => count($penjualanProduk),
            'total_produk' => array_sum(array_column($penjualanProduk, 'jumlah'))
        ];
        (new Penjualan)->update($penjualan_id, $update);
    }

    protected function afterUpdate($id, $data)
    {
        $penjualanProduk = (new $this->model)->where('id', $id)->first();
        $penjualan_id = $penjualanProduk['penjualan_id'];
        $penjualanProduk = (new $this->model)->where('penjualan_id', $penjualan_id)->findAll();

        $update = [
            'total_produk' => array_sum(array_column($penjualanProduk, 'jumlah'))
        ];
        (new Penjualan)->update($penjualan_id, $update);
    }
    
    protected function afterDelete($id)
    {
        $penjualan_id = $_GET['filter']['penjualan_id'];
        $penjualanProduk = (new $this->model)->where('penjualan_id', $penjualan_id)->findAll();

        $update = [
            'jumlah_produk' => count($penjualanProduk),
            'total_produk' => array_sum(array_column($penjualanProduk, 'jumlah'))
        ];
        (new Penjualan)->update($penjualan_id, $update);
    }

}