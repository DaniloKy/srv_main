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
            <a href="<?=base_url('login')?>">SIGN IN</a>
            <a href="<?=base_url('login')?>">PLAY</a>
        </li>
        <?php else: ?>
        <li>
            <a href="" class="dropdown">ACCOUNT</a>
            <div class="dropdown_content">
                <a href="<?=base_url('account_manage')?>">MANAGE ACCOUNT</a>
                <a href="<?=base_url('logout')?>">LOGOUT</a>
            </div>
            <a href="<?=base_url('game/character/list')?>">PLAY</a>
        </li>
        <?php endif; ?>
    </ul>
</nav>