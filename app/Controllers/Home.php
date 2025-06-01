<?php

namespace App\Controllers;

use App\Libraries\Page;

class Home extends BaseController
{
    public function dashboard(): string
    {
        $page = new Page;
        $page->setTitle('Dashboard');
        $page->setBreadcrumbs([
            [
                'label' => 'Dashboard',
                'url' => false
            ]
        ]);

        $db = db_connect();
        $presentaseProduk = $db->query('SELECT 
    p.nama AS produk,
    SUM(pp.jumlah_jadi) AS total_jadi,
    SUM(pp.jumlah_gagal) AS total_gagal,
    ROUND(SUM(pp.jumlah_gagal) / NULLIF(SUM(pp.jumlah_jadi) + SUM(pp.jumlah_gagal), 0) * 100, 2) AS persentase_gagal
FROM tb_produksi pp
JOIN tb_produk p ON pp.produk_id = p.id
GROUP BY pp.produk_id
ORDER BY persentase_gagal DESC');

        $produksiHarian = $db->query('SELECT 
    pp.tanggal,
    SUM(pp.jumlah_jadi) AS total_jadi,
    SUM(pp.jumlah_gagal) AS total_gagal
FROM tb_produksi pp
GROUP BY pp.tanggal
ORDER BY pp.tanggal DESC
LIMIT 30');

        $rop = $db->query('SELECT 
    b.id,
    b.nama,
    b.satuan,
    b.stok_minimum,
    (
        SELECT SUM(jumlah) 
        FROM tb_bahan_baku_stok 
        WHERE bahan_baku_id = b.id
    ) AS stok_saat_ini
FROM tb_bahan_baku b
HAVING stok_saat_ini <= stok_minimum
ORDER BY stok_saat_ini ASC');

        $avgLeadTime = $db->query('SELECT 
    bahan_baku_id,
    tb_bahan_baku.nama,
    AVG(DATEDIFF(tanggal, tanggal_pesan)) AS avg_lead_time
FROM `tb_bahan_baku_stok`
LEFT JOIN `tb_bahan_baku` ON tb_bahan_baku.id = tb_bahan_baku_stok.bahan_baku_id
WHERE supplier_id IS NOT NULL
GROUP BY bahan_baku_id');

        $forcasting = $db->query('SELECT 
    produk_id,
    tb_produk.nama,
    MONTH(tanggal) AS bulan,
    SUM(jumlah) AS total_terjual
FROM tb_penjualan_produk
JOIN tb_penjualan ON tb_penjualan.id = penjualan_id
JOIN tb_produk ON tb_produk.id = tb_penjualan_produk.produk_id
WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
GROUP BY produk_id, MONTH(tanggal)');

        $forcastBahan = $db->query('SELECT 
    k.bahan_baku_id,
    b.nama,
    SUM(k.jumlah * 500) AS kebutuhan_bulan_depan,
    b.satuan
FROM tb_produk_komposisi k
JOIN tb_bahan_baku b ON k.bahan_baku_id = b.id
GROUP BY k.bahan_baku_id');
        
        return $page->render('home/dashboard', compact('presentaseProduk', 'rop', 'avgLeadTime','produksiHarian','forcasting','forcastBahan'));
    }

    public function index(): string
    {
        return view('landing');
    }
}
