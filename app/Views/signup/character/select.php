<h1>CHOSE YOUR PLAYER</h1>
<div>
    <div>
    <?php if($characters && count($characters) > 0): ?>
        <?php foreach($characters as $character): ?>
            <div>
                <p>ben<?=$character?></p>
            </div>
        <?php endforeach; ?>
        <p><?=$characters?></p>
    <?php endif; ?>
    </div>
    
</div>