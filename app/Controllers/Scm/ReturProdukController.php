<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Libraries\Page;
use App\Models\Scm\Penjualan;
use App\Models\Scm\Produk;
use App\Models\Scm\Retur;
use App\Models\Scm\ReturProduk;

class ReturProdukController extends CrudController {

    protected $model = ReturProduk::class;

    protected function getTitle()
    {
        return 'Detail Retur';
    }

    protected function getSlug()
    {
        return 'retur-produk';
    }

    public function index()
    {
        $data = $this->getModel()->findAll();
        $retur = (new Retur())
                        ->select('tb_retur_penjualan.*, tb_kustomer.nama nama_kustomer')
                        ->where('tb_retur_penjualan.id', $_GET['filter']['retur_id'])
                        ->join('tb_penjualan', 'tb_penjualan.id = tb_retur_penjualan.penjualan_id')
                        ->join('tb_kustomer', 'tb_kustomer.id = tb_penjualan.kustomer_id')
                        ->first();

        $page = new Page;
        $page->setTitle('Data ' . $this->getTitle());
        $page->setSlug($this->getSlug());
        $page->setBreadcrumbs([
            [
                'label' => 'Retur',
                'url' => '/retur'
            ],
            [
                'label' => $retur['id'] .' - '. $retur['nama_kustomer'],
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
        $model->select('tb_retur_produk.*, tb_kustomer.nama nama_kustomer, tb_produk.nama nama_produk,tb_penjualan.tanggal tanggal_penjualan')
            ->where('retur_id', $_GET['filter']['retur_id'])
            ->join('tb_retur_penjualan', 'tb_retur_penjualan.id = tb_retur_produk.retur_id')
            ->join('tb_penjualan', 'tb_penjualan.id = tb_retur_penjualan.penjualan_id')
            ->join('tb_kustomer', 'tb_kustomer.id = tb_penjualan.kustomer_id')
            ->join('tb_produk', 'tb_produk.id = tb_retur_produk.produk_id')
            ;

        return $model;
    }

    protected function columns()
    {
        return [
            'tanggal_penjualan' => [
                'label' => 'Tanggal Penjualan'
            ],
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
        $retur_id = $_GET['filter']['retur_id'];
        $produk_id = $this->record ? $this->record['produk_id'] : 0;
        $produk = (new Produk)->where('id NOT IN (SELECT produk_id FROM tb_retur_produk WHERE retur_id = '.$retur_id.' AND produk_id <> '.$produk_id.')')->findAll();
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
        $data['retur_id'] = $_GET['filter']['retur_id'];

        return $data;
    }
    
    protected function beforeUpdate($data)
    {
        $data['retur_id'] = $_GET['filter']['retur_id'];

        return $data;
    }

    protected function afterInsert($request, $data)
    {
        $returProduk = (new $this->model)->where('id', $data)->first();
        $retur_id = $returProduk['retur_id'];
        $returProduk = (new $this->model)->where('retur_id', $retur_id)->findAll();

        $update = [
            'jumlah_produk' => count($returProduk),
            'total_produk' => array_sum(array_column($returProduk, 'jumlah'))
        ];
        (new Retur)->update($retur_id, $update);
    }

    protected function afterUpdate($id, $data)
    {
        $returProduk = (new $this->model)->where('id', $data)->first();
        $retur_id = $returProduk['retur_id'];
        $returProduk = (new $this->model)->where('retur_id', $retur_id)->findAll();

        $update = [
            'total_produk' => array_sum(array_column($returProduk, 'jumlah'))
        ];
        (new Retur)->update($retur_id, $update);
    }
    
    protected function afterDelete($id)
    {
        $retur_id = $_GET['filter']['retur_id'];
        $returProduk = (new $this->model)->where('retur_id', $retur_id)->findAll();

        $update = [
            'jumlah_produk' => count($returProduk),
            'total_produk' => array_sum(array_column($returProduk, 'jumlah'))
        ];
        (new Retur)->update($retur_id, $update);
    }

}