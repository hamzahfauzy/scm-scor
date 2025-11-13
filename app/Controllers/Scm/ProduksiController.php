<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Models\Scm\Komposisi;
use App\Models\Scm\Produk;
use App\Models\Scm\BahanBaku;
use App\Models\Scm\Produksi;
use App\Models\Scm\Informasi;
use App\Models\Scm\Stok;

class ProduksiController extends CrudController {

    protected $model = Produksi::class;

    protected function getTitle()
    {
        return 'Produksi';
    }

    protected function getSlug()
    {
        return 'produksi';
    }

    protected function getModel()
    {
        $model = (new $this->model)->select('tb_produksi.*, tb_produk.nama nama_produk')->join('tb_produk','tb_produk.id = tb_produksi.produk_id');
        return $model;
    }

    protected function columns()
    {
        return [
            'tanggal' => [
                'label' => 'Tanggal'
            ],
            'nama_produk' => [
                'label' => 'Produk'
            ],
            'jumlah_jadi' => [
                'label' => 'Jumlah Jadi'
            ],
            'jumlah_gagal' => [
                'label' => 'Jumlah Gagal'
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
        $produk = (new Produk)->findAll();
        $options = [0 => 'Pilih Produk'];
        foreach($produk as $item)
        {
            $options[$item['id']] = $item['nama'];
        }
        return [
            'tanggal' => [
                'label' => 'Tanggal',
                'type' => 'date',
                'default_value' => date('Y-m-d')
            ],
            'produk_id' => [
                'label' => 'Produk',
                'type' => 'select',
                'options' => $options
            ],
            'jumlah_jadi' => [
                'label' => 'Jumlah Jadi',
                'type' => 'number',
            ],
            'jumlah_gagal' => [
                'label' => 'Jumlah Gagal',
                'type' => 'number',
            ],
            'keterangan' => [
                'label' => 'Keterangan',
                'type' => 'textarea',
            ],
        ];
    }

    protected function afterInsert($request, $data)
    {
        $komposisi = (new Komposisi)->where('produk_id', $request['produk_id'])->findAll();

        foreach($komposisi as $item)
        {
            (new Stok)->insert([
                'tanggal' => $request['tanggal'],
                'bahan_baku_id' => $item['bahan_baku_id'],
                'jumlah' => (-1 * $item['jumlah']) * ($request['jumlah_jadi'] + $request['jumlah_gagal']),
                'keterangan' => 'jangan diubah -> produksi_id:' . $data,
                'tanggal_pesan' => $request['tanggal']
            ]);

            $bahanBaku = (new BahanBaku)->select('tb_bahan_baku.*, 
                            CASE WHEN COALESCE((SELECT SUM(jumlah) FROM tb_bahan_baku_stok WHERE bahan_baku_id = tb_bahan_baku.id),0) < tb_bahan_baku.stok_minimum THEN "Warning" ELSE "OK" END as status_stok')
                            ->where('id', $item['bahan_baku_id'])
                            ->first();
            if($bahanBaku['status_stok'] == 'Warning')
            {
                (new Informasi)->insert([
                    'bahan_baku_id' => $item['bahan_baku_id'],
                    'keterangan' => 'Stok untuk bahan baku '.$bahanBaku['nama'].' telah berstatus warning. Jangan lupa untuk memesan agar stok terjaga'
                ]);
            }
        }
    }
    
    protected function afterUpdate($id, $data)
    {
        $komposisi = (new Komposisi)->where('produk_id', $data['produk_id'])->findAll();

        foreach($komposisi as $item)
        {
            // find stok
            (new Stok)->where('bahan_baku_id', $item['bahan_baku_id'])->like('keterangan','produksi_id:' . $id)->update([
                'jumlah' => (-1 * $item['jumlah']) * ($data['jumlah_jadi'] + $data['jumlah_gagal']),
            ]);
        }
    }

    protected function beforeDelete($data)
    {
        $produksi = (new Produksi)->where('id', $data)->first();
        $komposisi = (new Komposisi)->where('produk_id', $produksi['produk_id'])->findAll();

        foreach($komposisi as $item)
        {
            // find stok
            $stok = (new Stok)->where('bahan_baku_id', $item['bahan_baku_id'])->like('keterangan','produksi_id:' . $data)->first();
            if($stok)
            {
                (new Stok)->delete($stok['id']);
            }
        }
    }

}