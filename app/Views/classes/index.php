<div class="main_info">
    <?php if($classes && count($classes) > 0): ?>
        <?php foreach($classes as $c): ?>
        <div class="general_details " id="<?=$c->name_compiled?>-details">
            <div class="class_selected" >
                <h2><?=$c->name?></h2>
                <hr />
                <img src="<?=base_url('images/publish/classes/'.$c->image_path)?>" alt="<?=$c->name_compiled?>">
            </div>
            <div class="class_details">
                <h3>Class Info</h3>
                <p><?=$c->description?></p>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif;?>
</div>     