<div>
    <div class="ann_intro">
        <h1 class="ann_title">Announcements</h1>
    </div>
    <div class="ann_list">
    <?php if($announcements && count($announcements) > 0): ?>
        <?php foreach($announcements as $ann): ?>
        <a href="<?=base_url('announcement/'.$ann->title_compiled.'/'.$ann->title_compiled);?>" class="ann_card">
            <img src="<?=base_url('images/thumb/announcements/'.$ann->image_path)?>" alt="<?=$ann->title_compiled?>">
            <h2><?=$ann->title?></h2>
        </a>
        <?php endforeach; ?>
    <?php endif;?>
    </div>
    <div class="tags_list">
    <?php if($tags && count($tags) > 0): ?>
        <?php foreach($tags as $tag): ?>
        <a href="<?=base_url('announcements/'.$tag->tag_compiled);?>" class="tag_card">
            <img src="<?=base_url('images/thumb/announcements/'.$tag->tag_compiled)?>" alt="<?=$tag->tag?>">
            <h4><?=$tag->tag?></h4>
        </a>
        <?php endforeach; ?>
    <?php endif;?>
    </div>
</div>     