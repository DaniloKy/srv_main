<h1>CREATE YOUR NEW PLAYER</h1>
<div>
    <form action="<?=base_url("game/character/create")?>" method="POST">
        <div class="create_container">
        <?php if($classes && count($classes) > 0): ?>
            <?php foreach($classes as $class): ?>
            <div class="details">
                <p><?=$class->name?></p>
                <img src="<?=base_url('images/'.$class->name_compiled.".svg")?>" alt="<?=$class->name_compiled?>">
                <p><?=$class->description?></p>
            </div>
            <label class="select_character">
                <p><?=$class->name?></p>
                <input type="radio" class="visually-hidden" required hidden name="character" value=""/>
            </label>
            <?php endforeach; ?>
        <?php endif;?>
        </div>
        <button type="submit" class="select_btn">CREATE</button>
    </form>
    <?php if(session('error')): ?>
        <p class="alert"><?=session('error');?></p>
    <?php endif; ?>
</div>