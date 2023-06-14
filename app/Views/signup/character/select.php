<h1>CHOSE YOUR PLAYER</h1>
<div>
    <form action="<?=base_url("game/character/select")?>" method="POST">
        <div class="container">
        <?php if(isset($characters[0])): ?>
            <label class="card">
                <img src="<?=base_url('images/'.$characters[0]->class.".svg")?>" alt="<?=$characters[0]->class?>">
                <input type="radio" class="visually-hidden" required hidden name="character" value="<?=$characters[0]->id?>"/>
            </label>
        <?php else:?>
            <div class="card">
                <a href="<?=base_url('game/character/create')?>" class="plus-icon"><div>+</div></a>
            </div>
        <?php endif; ?>
        <?php if(isset($characters[1])): ?>
            <label class="card">
                <img src="<?=base_url('images/'.$characters[1]->class.".svg")?>" alt="<?=$characters[1]->class?>">
                <input type="radio" class="visually-hidden" hidden name="character" value="<?=$characters[1]->id?>"/>
            </label>
        <?php else:?>
            <div class="card" onclick="selectCard(this)">
                <a href="<?=base_url('game/character/create')?>"><div class="plus-icon">+</div></a>
            </div>
        <?php endif; ?>
        <?php if(isset($characters[2])): ?>
            <label class="card">
                <img src="<?=base_url('images/'.$characters[2]->class.".svg")?>" alt="<?=$characters[2]->class?>">
                <input type="radio" class="visually-hidden" hidden name="character" value="<?=$characters[2]->id?>"/>
            </label>
        <?php else:?>
            <div class="card">
                <a href="<?=base_url('game/character/create')?>" class="plus-icon"><div>+</div></a>
            </div>
        <?php endif; ?>
        </div>
        <button type="submit" class="select_btn">SELECT</button>
    </form>
    <?php if(session('error')): ?>
        <p class="alert"><?=session('error');?></p>
    <?php endif; ?>
</div>