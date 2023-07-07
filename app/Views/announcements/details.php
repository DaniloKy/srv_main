<div class="ann_details_info">
    <img src="<?=base_url('images/publish/announcements/'.$announcement->image_path)?>" alt="<?=$announcement->title;?>">
    <div class="ann_details">
        <h1 class="ann_title"><?=$announcement->title;?></h1>
        <p class="ann_description"><?=$announcement->description;?></p>
        <p class="credits"><?=$announcement->username.' - '.date("Y-m-d", strtotime($announcement->created_at));?></p>
    </div>
</div>