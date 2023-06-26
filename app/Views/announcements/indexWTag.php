<div>
    <div class="ann_intro">
        <h1 class="ann_title"><?=$tag?></h1>
    </div>
    <div class="ann_list">
    <?php if($announcements && count($announcements) > 0): ?>
        <?php foreach($announcements as $ann): ?>
        <a href="<?=base_url('announcements/'.$ann->title_compiled);?>" class="ann_card">
            <img src="<?=base_url('images/thumb/announcements/'.$ann->image_path)?>" alt="<?=$ann->title_compiled?>">
            <h2><?=$ann->title?></h2>
        </a>
        <?php endforeach; ?>
    <?php endif;?>
    </div>
</div>     