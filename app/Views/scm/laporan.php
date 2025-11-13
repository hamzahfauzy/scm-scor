<?= $this->extend('layouts/app') ?>

<?= $this->section('pageTitle') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<form class="d-flex w-100 justify-content-between">
    <div class="form-group w-100">
        <label for="">Tanggal Awal</label>
        <input type="date" class="form-control" name="filter[start_date]" value="<?=$filter['start_date']?>">
    </div>
    <div class="form-group w-100">
        <label for="">Tanggal Akhir</label>
        <input type="date" class="form-control" name="filter[end_date]" value="<?=$filter['end_date']?>">
    </div>
    <div class="form-group w-100">
        <label for="" style="opacity:0">Label</label>
        <div class="d-flex w-100" style="gap:10px">
            <button class="btn btn-success w-100">Filter</button>
            <a href="/laporan/cetak?filter[start_date]=<?=$filter['start_date']?>&filter[end_date]=<?=$filter['end_date']?>" class="btn btn-primary w-100" target="_blank">Cetak</a>
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <?php foreach($columns as $key => $column): ?>
                <th><?=$column['label']?></th>
                <?php endforeach ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $index => $list): ?>
            <tr>
                <td><?=$index+1?></td>
                <?php foreach($columns as $key => $column): ?>
                <td><?=$list[$key]?></td>
                <?php endforeach ?>
            </tr>
            <?php endforeach ?>

            <?php if(empty($data)): ?>
            <tr>
                <td colspan="<?=count($columns)+2?>"><i>Tidak ada data</i></td>
            </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>