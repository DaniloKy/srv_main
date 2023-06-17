<div class="character_create">
    <h2>SELECT CLASS</h2>
    <?php foreach(validation_errors() as $error): ?>
        <p class="alert"><?=$error?></p>
    <?php endforeach; ?>
    <?php if(session('creation_error')): ?>
    <p class="alert"><?=session('creation_error');?></p>
    <?php endif; ?>
    <form class="create_container form" action="<?=base_url("game/character/create")?>" method="POST">
        <div class="main_info">
            <div class="class_head">    
                <h3>Class List</h3>
                <div class="class_list">
                    <?php if($classes && count($classes) > 0): ?>
                        <?php foreach($classes as $c): ?>
                            <?php if($c->name_compiled == $class) $current_class = $c?>
                            <div class="card_class">
                                <p><?=$c->name?></p>
                                <input type="radio" class="visually-hidden" required name="character" id="selected_character" <?php if($c->name_compiled == $class) echo 'checked'?> value="<?=$c->name_compiled?>"/>
                                <label for="selected_character" class="select_character">
                                    <a href="?class=<?=$c->name_compiled?>"><img src="<?=base_url('images/classes/'.$c->name_compiled.".svg");?>" alt="<?=$c->name?>"></a>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif;?>
                </div>
            </div>
            <?php if(isset($current_class)):?>
            <div class="general_details " id="<?=$current_class->name_compiled?>-details">
                <div class="class_selected" >
                    <h2><?=$current_class->name?></h2>
                    <hr />
                    <p><?=$current_class->tiny_description?></p>
                    <img src="<?=base_url('images/classes/'.$current_class->name_compiled.".svg")?>" alt="<?=$current_class->name_compiled?>">
                </div>
                <div class="class_details">
                    <h3>Class Info</h3>
                    <p><?=$current_class->description?></p>
                </div>
            </div>
            <?php endif;?>
        </div>
        <div class="main_inputs">
            <a href="<?=base_url('game/character/list')?>" class="btn">Back</a>
            <div>
                <label for="">CHARACTER NAME</label><br>
                <input type="text" name="character_name" value="<?= old('character_name') ?>" placeholder="ID" >
            </div>
            <button type="submit" class="select_btn">Confirm</button>
        </div>                        
    </form>
    <?php if(session('error')): ?>
        <p class="alert"><?=session('error');?></p>
    <?php endif; ?>
</div>