<?php

namespace App\Config;

class Menu {

    static function buildItem($label, $url = false, $slug = '', $icon = '', $controller = false)
    {
        $url = !$url ? "/$slug" : $url;
        return [
            'label' => $label,
            'icon'  => $icon,
            'slug' => $slug,
            'url' => $url,
            'controller' => $controller
        ];
    }

    static function get()
    {
        $level = session()->get('level');
        
        if($level == 'Supplier')
        {

            $notifCount = (new \App\Models\Scm\Stok)->where('status', 'REQUEST')->countAllResults();

            $label = $notifCount ? '<span class="badge badge-danger">'.$notifCount.'</span>' : '';

            return [
                static::buildItem('Bahan Baku', false, 'bahan-baku-supplier', 'fas fa-box', 'Scm\BahanBakuSupplierController'),
                // static::buildItem('Bahan Baku', false, 'bahan-baku', 'fas fa-box', 'Scm\BahanBakuController'),
                static::buildItem('Permintaan Stok ' . $label, false, 'stok', 'fas fa-box', 'Scm\StokController'),
                static::buildItem('Laporan ', false, 'laporan', 'fa fa-print', 'Scm\LaporanController'),
            ];
        }
        
        if($level == 'Kustomer')
        {
            return [
                static::buildItem('Pembelian', false, 'penjualan', 'fas fa-box', 'Scm\PenjualanController'),
            ];
        }

        $notifCount = (new \App\Models\Scm\Informasi)->where('status', null)->countAllResults();

        $label = $notifCount ? '<span class="badge badge-danger">'.$notifCount.'</span>' : '';

        return [
            static::buildItem('Supplier', false, 'suplier', 'fas fa-users', 'Scm\SuplierController'),
            static::buildItem('Bahan Baku', false, 'bahan-baku', 'fas fa-box', 'Scm\BahanBakuController'),
            static::buildItem('Produk', false, 'produk', 'fas fa-box', 'Scm\ProdukController'),
            // static::buildItem('Kustomer', false, 'kustomer', 'fas fa-users', 'Scm\KustomerController'),
            // static::buildItem('Penjualan', false, 'penjualan', 'fas fa-users', 'Scm\PenjualanController'),
            // static::buildItem('Retur', false, 'retur', 'fas fa-undo-alt', 'Scm\ReturController'),
            static::buildItem('Produksi', false, 'produksi', 'fas fa-box', 'Scm\ProduksiController'),
            static::buildItem('Informasi ' . $label, false, 'informasi', 'fa fa-bell', 'Scm\InformasiController'),
            static::buildItem('Laporan ', false, 'laporan', 'fa fa-print', 'Scm\LaporanController'),
        ];
    }
}