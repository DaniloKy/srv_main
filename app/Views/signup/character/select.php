<h1>CHOSE YOUR PLAYER</h1>

<?php foreach($characters as $character): ?>
    <?php if(isset($character)): ?>
    <dialog data-characterId="<?=$character->id?>" class="confirm_delete">
        <p>Are you sure you want to delete '<?=$character->username?>'?</p>
        <form action="<?=base_url('game/character/delete')?>" method="post">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="character_name" value="<?=$character->username?>">
            <button type="submit">YES</button>
            <button formmethod="dialog">NO</button>
        </form>
    </dialog>
    <?php endif; ?>
<?php endforeach;?>

<div>
    <form action="<?=base_url("game/character/select")?>" method="POST" class="select_container">
        <div class="container">
        <?php for($i = 0; $i < env('MAX_CHARACTERS'); $i++): ?>
            <?php $character = $characters[$i]??null;?>
            <?php if(isset($character)): ?>
            <div class="card">
                <button type="button" data-characterId="<?=$character->id?>">X</button>
                <label>
                    <h2><?=$character->username?></h2>
                    <img src="<?=base_url('images/classes/'.$character->class_name.".svg")?>" alt="<?=$character->class_name?>">
                    <input type="radio" class="visually-hidden" required hidden name="character" value="<?=$character->id?>"/>
                </label>
            </div>
            <?php else:?>
                <div class="card">
                    <a href="<?=base_url('game/character/create')?>" class="plus-icon"><div>+</div></a>
                </div>
            <?php endif; ?>
        <?php endfor;?>
        </div>
        <button type="submit" class="class_btn select_btn">SELECT</button>
    </form>
    <?php if(session('error')): ?>
        <p class="alert"><?=session('error');?></p>
    <?php endif; ?>
</div>