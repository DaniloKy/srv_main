<div>
    <?php if($announcements && count($announcements) > 0): ?>
    <div class="ann_intro">
        <h1 class="ann_title"><?=$announcements[0]->tag?></h1>
    </div>
    <div class="ann_list">
        <?php foreach($announcements as $ann): ?>
        <div class="ann_info">
            <a href="<?=base_url('announcement/'.$ann->tag_compiled.'/'.$ann->title_compiled);?>" class="ann_card">
                <img src="<?=base_url('images/thumb/announcements/'.$ann->image_path)?>" alt="<?=$ann->title_compiled?>">
                <div class="ann_details">
                    <p class="tag"><?=$ann->tag?></p>
                    <h2><?=$ann->title?></h2>
                    <p class="credits"><?=$ann->username.' - '.date("Y-m-d", strtotime($ann->created_at))?></p>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    <?php endif;?>
    </div>
</div>     