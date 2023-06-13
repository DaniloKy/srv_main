<h1>CHOSE YOUR PLAYER</h1>
<div>
    <div>
    <p><?print_r($characters)?></p>
    <?php if($characters && count($characters) > 0): ?>
        <?php foreach($characters as $character): ?>
            <div>
                <p><?print_r($character)?></p>
            </div>
        <?php endforeach; ?>
        <p><?print_r($characters)?></p>
    <?php endif; ?>
    </div>
    
</div>