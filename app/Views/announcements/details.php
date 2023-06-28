<div class="ann_details_info">
    <img src="<?=base_url('images/publish/announcements/'.$announcement->image_path)?>" alt="<?=$announcement->title;?>">
    <div class="ann_intro">
        <p class="ann_subtitle"><?=$announcement->username;?></p>
        <p class="ann_subtitle"><?=$announcement->created_at;?></p>
        <h1 class="ann_title"><?=$announcement->title;?></h1>
        <p class="ann_description"><?=$announcement->description;?></p>
    </div>
</div>