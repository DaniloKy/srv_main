<div class="character_create">
    <h2>SELECT CLASS</h2>
    <form class="create_container form" action="<?=base_url("game/character/create")?>" method="POST">
        <div class="main_info">
            <div class="class_list">
                <h3>Class List</h3>
                <?php if($classes && count($classes) > 0): ?>
                    <?php foreach($classes as $class): ?>
                        <div class="card_class">
                            <p><?=$class->name?></p>
                            <input type="radio" class="visually-hidden" required name="character" id="selected_character" value="<?=$class->name_compiled?>"/>
                            <label for="selected_character" class="select_character">
                                <img src="<?=base_url('images/classes/'.$class->name_compiled.".svg");?>" alt="<?=$class->name?>">
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif;?>
            </div>
            <div class="general_details " id="<?=$class->name_compiled?>-details">
                <div class="class_selected" >
                    <h2><?=$class->name?></h2>
                    <hr />
                    <p><?=$class->tiny_description?></p>
                    <img src="<?=base_url('images/classes/'.$class->name_compiled.".svg")?>" alt="<?=$class->name_compiled?>">
                </div>
                <div class="class_details">
                    <h3>Class Info</h3>
                    <p><?=$class->description?></p>
                </div>
            </div>
        </div>
        <div class="main_inputs">
            <a href="<?=base_url('game/character/list')?>" class="btn">Back</a>
            <div>
                <label for="">CHARACTER NAME</label><br>
                <input type="text" name="charName" placeholder="ID" >
            </div>
            <button type="submit" class="select_btn">Confirm</button>
        </div>                        
    </form>
    <?php if(session('error')): ?>
        <p class="alert"><?=session('error');?></p>
    <?php endif; ?>
</div>