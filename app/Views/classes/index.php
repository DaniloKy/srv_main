<div>
    <div class="classes_intro">
        <h3 class="classes_subtitle">Chose your</h3>
        <h1 class="classes_title">Class</h1>
        <p class="classes_description">Each class represents a unique playstyle, abilities, and specialized skills that define the character's role in the game world. Classes often dictate the type of weapons, armor, and spells a character can use, as well as their strengths and weaknesses in combat or other activities.</p>
    </div>
    <div class="classes_list">
    <?php if($classes && count($classes) > 0): ?>
        <?php foreach($classes as $c): ?>
        <a href="<?=base_url('classes/'.$c->name_compiled);?>" class="class_card">
            <img src="<?=base_url('images/thumb/classes/'.$c->image_path)?>" alt="<?=$c->name_compiled?>">
            <h2><?=$c->name?></h2>
        </a>
        <?php endforeach; ?>
    <?php endif;?>
    </div>
</div>     