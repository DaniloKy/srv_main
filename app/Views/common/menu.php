<nav >
    <ul class="navbar-list">
        <li>
            <a href="<?=base_url('/')?>">SRV UTP</a>
        </li>
        <li>
            <a href="#">GAME</a>
        </li>
        <li>
            <a href="#">CLASSES</a>
        </li>
        <li>
            <a href="#">NEWS</a>
        </li>
        <li>
            <a href="#">PATCH NOTES</a>
        </li>
        <?php if(session('userdata') == null || !session('userdata')['logged_in']): ?>
        <li>
            <a href="<?=base_url('login')?>">SING IN</a>
            <a href="<?=base_url('login')?>">PLAY</a>
        </li>
        <?php else: ?>
        <li>
            <a href="<?=base_url('logout')?>">LOGOUT</a>
            <a href="<?=base_url('game/character/list')?>">PLAY</a>
        </li>
        <?php endif; ?>
    </ul>
</nav>