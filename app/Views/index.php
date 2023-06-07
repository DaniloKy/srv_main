<p>Srv Utp</p>
<?php if(session('userdata')): ?>
<p><?=session('userdata')['user']['email']?></p>
<p><?php print_r($_COOKIE) ?></p>
<?php endif; ?>