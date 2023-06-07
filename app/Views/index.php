<p>Srv Utp</p>
<?=session_id()?>
<?php if(session('userdata')): ?>
<p><?php print_r(session('userdata'))?></p>
<p><?php print_r($_COOKIE) ?></p>
<?php endif; ?>