<nav >
    <ul class="navbar-list">
        <li>
            <a href="<?=base_url('/')?>">SRV UTP</a>
        </li>
        <li>
            <a href="<?=base_url('how-to-play')?>">GAME</a>
        </li>
        <li>
            <a href="<?=base_url('classes')?>">CLASSES</a>
        </li>
        <li>
            <a href="<?=base_url('announcements/news')?>">NEWS</a>
        </li>
        <li>
            <a href="<?=base_url('announcements/patch_notes')?>">PATCH NOTES</a>
        </li>
        <?php if(session('userdata') == null || !session('userdata')['logged_in']): ?>
        <li>
            <a href="<?=base_url('login');?>">SIGN IN</a>
            <a href="<?=base_url('login');?>">PLAY</a>
        </li>
        <?php else: ?>
        <li>
            <?php if(session('userdata')['user']['super']):?>
                <a href="<?=base_url('user/admin/manage');?>"><i class="bx bx-crown bx-sm"></i></a>
            <?php endif;?>
            <span class="dropdown"><i class="bx bx-user-circle bx-sm"></i></span>
            <div class="dropdown_content visually-hidden">
                <a href="<?=base_url('user/manage')?>">ACCOUNT</a>
                <a href="<?=base_url('logout')?>">LOGOUT</a>
            </div>
            <a href="<?=base_url('game/character/list')?>">PLAY</a>
        </li>
        <?php endif; ?>
    </ul>
</nav>
<main>
