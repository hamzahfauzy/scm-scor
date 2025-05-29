<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Presentase Produk</div>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th>Total Jadi</th>
                            <th>Total Gagal</th>
                            <th>Presentase</th>
                        </tr>
                        <?php foreach($presentaseProduk->getResult('array') as $index => $p): ?>
                        <tr>
                            <td><?=$index+1?></td>
                            <td><?=$p['produk']?></td>
                            <td><?=number_format($p['total_jadi'],2)?></td>
                            <td><?=number_format($p['total_gagal'],2)?></td>
                            <td><?=number_format($p['persentase_gagal'],2)?> %</td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Produksi Harian</div>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Total Jadi</th>
                            <th>Total Gagal</th>
                        </tr>
                        <?php foreach($produksiHarian->getResult('array') as $index => $p): ?>
                        <tr>
                            <td><?=$index+1?></td>
                            <td><?=$p['tanggal']?></td>
                            <td><?=number_format($p['total_jadi'],2)?></td>
                            <td><?=number_format($p['total_gagal'],2)?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Reorder Poin</div>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Satuan</th>
                            <th>Stok Minimum</th>
                            <th>Stok Saat ini</th>
                        </tr>
                        <?php foreach($rop->getResult('array') as $index => $p): ?>
                        <tr>
                            <td><?=$index+1?></td>
                            <td><?=$p['nama']?></td>
                            <td><?=$p['satuan']?></td>
                            <td><?=number_format($p['stok_minimum'],2)?></td>
                            <td><?=number_format($p['stok_saat_ini'],2)?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Rata-rata Waktu Pemesanan</div>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Rata-Rata</th>
                        </tr>
                        <?php foreach($avgLeadTime->getResult('array') as $index => $p): ?>
                        <tr>
                            <td><?=$index+1?></td>
                            <td><?=$p['nama']?></td>
                            <td><?=number_format($p['avg_lead_time'],0)?> Hari</td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Forcast Penjualan</div>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Bulan</th>
                            <th>Total Terjual</th>
                        </tr>
                        <?php foreach($forcasting->getResult('array') as $index => $p): ?>
                        <tr>
                            <td><?=$index+1?></td>
                            <td><?=$p['nama']?></td>
                            <td><?=$p['bulan']?></td>
                            <td><?=number_format($p['total_terjual'],2)?> Kg</td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Forcast Kebutuhan Bahan Baku</div>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kebutuhan Bulan Depan</th>
                        </tr>
                        <?php foreach($forcastBahan->getResult('array') as $index => $p): ?>
                        <tr>
                            <td><?=$index+1?></td>
                            <td><?=$p['nama']?></td>
                            <td><?=number_format($p['kebutuhan_bulan_depan'],0)?> <?=$p['satuan']?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>