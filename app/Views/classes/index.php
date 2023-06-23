<div>
    <?php if($classes && count($classes) > 0): ?>
        <?php foreach($classes as $c): ?>
        <div id="<?=$c->name_compiled?>-details">
            <div>
                <h2><?=$c->name?></h2>
                <hr />
                <img src="<?=base_url('images/publish/classes/'.$c->image_path)?>" alt="<?=$c->name_compiled?>">
            </div>
            <div>
                <p><?=$c->description?></p>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif;?>
</div>     