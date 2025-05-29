<?= $this->extend('layouts/app') ?>

<?= $this->section('pageTitle') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <form action="" method="POST">
        <div class="card-body">
            <?= csrf_field() ?>
            
            <?= \App\Libraries\Form::generate($fields, $data) ?>
        
        </div>
        
        <div class="card-action">
            <button type="submit" class="btn btn-success">Submit</button>
            <a href="<?= site_url($page_slug . (isset($_GET['filter']) ? '?'.http_build_query($_GET) : ''))?>" class="btn btn-warning">Kembali</a>    
        </div>
    </form>
</div>
<?= $this->endSection() ?>