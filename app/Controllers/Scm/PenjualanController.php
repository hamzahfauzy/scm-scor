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

        if(session()->get('level') == 'Kustsomer')
        {
            $model->where('tb_kustomer.user_id', session()->get('id'));
        }

        return $model;
    }

    protected function getTitle()
    {
        return session()->get('level') == 'Admin' ? 'Penjualan' : 'Pembelian';
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
            'status' => [
                'label' => 'Status'
            ]
        ];
    }

    protected function fields()
    {
        if(session()->get('level') == 'Admin')
        {
            $options = [0 => 'Pilih Kustomer'];
            $kustomer = (new Kustomer)->findAll();
            foreach($kustomer as $item)
            {
                $options[$item['id']] = $item['nama'];
            }
        }
        else
        {
            $kustomer = (new Kustomer)->where('user_id', session()->get('id'))->first();
            $options = [
                $kustomer['id'] => $kustomer['nama']
            ];
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
        $request['status'] = session()->get('level') == 'Admin' ? 'CONFIRM' : 'REQUEST';

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
        $btn = '<a href="/penjualan-produk?filter[penjualan_id]='.$data['id'].'" class="btn btn-sm btn-info">Detail</a>';

        if($data['status'] == 'REQUEST')
        {
            $btn .= ' <a href="/penjualan/confirm/'.$data['id'].'" class="btn btn-sm btn-success" onclick="return confirm(\'Apakah anda yakin konfirmasi penjualan ini ?\')">Konfirmasi</a>';
        }

        return $btn;
    }

    function confirm($id)
    {
        (new Penjualan)->update($id, [
            'status' => 'CONFIRM'
        ]);

        return redirect()->to('/'. $this->getSlug() . (isset($_GET['filter']) ? '?'.http_build_query($_GET) : ''));
    }

}