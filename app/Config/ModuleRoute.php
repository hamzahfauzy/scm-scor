<?php

namespace App\Config;

class ModuleRoute {
    
    static function get()
    {
        return [
            'komposisi' => 'Scm\KomposisiController',
            'penjualan-produk' => 'Scm\PenjualanProdukController',
            'retur-produk' => 'Scm\ReturProdukController',
            'stok' => 'Scm\StokController',
        ];
    }
}