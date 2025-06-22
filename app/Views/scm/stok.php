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