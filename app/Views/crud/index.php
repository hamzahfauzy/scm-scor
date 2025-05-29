<?= $this->extend('layouts/app') ?>

<?= $this->section('pageTitle') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <?php foreach($columns as $key => $column): ?>
                <th><?=$column['label']?></th>
                <?php endforeach ?>
                <th>
                    <a href="<?=site_url("/$page_slug/create" . (isset($_GET['filter']) ? '?'.http_build_query($_GET) : ''))?>" class="btn btn-success btn-sm">Tambah Data</a>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $index => $list): ?>
            <tr>
                <td><?=$index+1?></td>
                <?php foreach($columns as $key => $column): ?>
                <td><?=$list[$key]?></td>
                <?php endforeach ?>
                <td>
                    <?= $detail_button($list) ?>
                    
                    <a href="<?= site_url("/$page_slug/$list[id]/edit" . (isset($_GET['filter']) ? '?'.http_build_query($_GET) : '')) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <form action="<?= site_url("/$page_slug/$list[id]" . (isset($_GET['filter']) ? '?'.http_build_query($_GET) : '')) ?>" method="post" style="display:inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" onclick="return confirm('Apakah kamu yakin akan menghapus data ini?')" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
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