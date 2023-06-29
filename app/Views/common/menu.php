<nav >
    <ul class="navbar-list">
        <li class="logo">
            <a href="<?=base_url('/')?>"><p><i class="bx bx-lg bxl-postgresql" ></i></p></a>
        </li>
        <li>
            <a href="<?=base_url('how-to-play')?>">Game</a>
        </li>
        <li>
            <a href="<?=base_url('classes')?>">Classes</a>
        </li>
        <li>
            <a href="<?=base_url('announcements')?>">Announcements</a>
        </li>
        <li>
            <a href="<?=base_url('announcements/news')?>">News</a>
        </li>
        <li>
            <a href="<?=base_url('announcements/patch_notes')?>">Patch notes</a>
        </li>
        <?php if(session('userdata') == null || !session('userdata')['logged_in']): ?>
        <li class="last">
            <a href="<?=base_url('login');?>">Sing in</a>
            <a class="play_btn" href="<?=base_url('login');?>">Play</a>
        </li>
        <?php else: ?>
        <li class="last">
            <?php if(session('userdata')['user']['super']):?>
                <a href="<?=base_url('user/admin/manage');?>"><i class="bx bx-crown bx-sm"></i></a>
            <?php endif;?>
            <span class="dropdown"><i class="bx bx-user-circle bx-sm"></i></span>        
            <a class="play_btn" href="<?=base_url('game/character/list')?>">PLAY</a>
        </li>
        <?php endif; ?>
    </ul>
    <div class="dropdown_content visually-hidden">
        <a href="<?=base_url('user/manage')?>"><p>Account</p></a>
        <a href="<?=base_url('logout')?>"><p>Logout</p></a>
    </div>
</nav>
<main>
