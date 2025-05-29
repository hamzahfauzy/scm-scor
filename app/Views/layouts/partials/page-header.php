<div class="page-header">
    <h4 class="page-title"><?= $page_title ?></h4>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="/">
                <i class="icon-home"></i>
            </a>
        </li>
        <?php foreach($page_breadcrumbs as $breadcrumb): ?>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <?php if($breadcrumb['url']): ?>
            <a href="<?=$breadcrumb['url']?>"><?=$breadcrumb['label']?></a>
            <?php else: ?>
            <?=$breadcrumb['label']?>
            <?php endif ?>
        </li>
        <?php endforeach ?>
    </ul>
</div>