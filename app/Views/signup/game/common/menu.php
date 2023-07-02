<nav >
  <ul class="navbar-list">
    <li class="<?=active_link('lobby'); ?>">
        <a href="<?=base_url('game/lobby')?>">PLAY</a>
    </li>
    <li class="<?=active_link('stash'); ?>">
        <a href="#">STASH</a>
    </li>
    <li class="<?=active_link('skills'); ?>">
        <a href="#">SKILLS</a>
    </li>
    <li class="<?=active_link('perks'); ?>">
        <a href="#">PERKS</a>
    </li>
    <li class="<?=active_link('shop'); ?>">
        <a href="#">SHOP</a>
    </li>
    <li class="<?=active_link('career'); ?>">
        <a href="<?=base_url('game/career')?>">CAREER</a>
    </li>
    <li id="players_list_btn">
        <span><i class='bx bx-group bx-sm'></i></span>
    </li>
  </ul>
</nav>
<div class="players_list visually-hidden">
    <h2>Online</h2>
    <p>xXGamerXx<i class='bx bxs-circle online_circle'></i></p>
</div>
<main>