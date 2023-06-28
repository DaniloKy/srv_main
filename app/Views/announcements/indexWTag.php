<div>
    <?php if($announcements && count($announcements) > 0): ?>
    <div class="ann_intro">
        <h1 class="ann_title"><?=$announcements[0]->tag?></h1>
    </div>
    <div class="ann_list">
        <?php foreach($announcements as $ann): ?>
        <a href="<?=base_url('announcement/'.$ann->tag_compiled.'/'.$ann->title_compiled);?>" class="ann_card">
            <img src="<?=base_url('images/thumb/announcements/'.$ann->image_path)?>" alt="<?=$ann->title_compiled?>">
            <p><?=$ann->username?></p>
            <p><?=$ann->created_at?></p>
            <h2><?=$ann->title?></h2>
        </a>
        <?php endforeach; ?>
    <?php endif;?>
    </div>
</div>     