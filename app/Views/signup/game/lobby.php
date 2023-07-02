<div class="lobby">
    <div class="logo">
        <i class="bx bx-lg bxl-postgresql"></i>
        <h1>Survive Utopia</h1>
    </div>
    <div class="player_info">
        <div class="intro">
            <h2><?=$playerInfo->username?></h2>
            <h3><?=$playerInfo->name?></h3>
            <h5><?=$playerInfo->tiny_description?></h5>
        </div>
        <div class="level">
            <label for="player_xp">
            <div class="level_txt">
                <p>Lvl. <span class="level_number"><?=$playerInfo->playerLevel['level'] ?></span></p>
                <p><span class="level_number"><?=$playerInfo->playerLevel['xpTo'] ?></span>xp To</p>
                <p>Lvl. <span class="level_number"><?=$playerInfo->playerLevel['nextLevel'] ?></span></p>
            </div>
            </label>
            <progress id="disk_d" class="xp-bar" value="<?=$playerInfo->playerLevel['progress'] ?>" max="1"></progress>
        </div>
        <div>
            <a class="btn changeClass" href="<?=base_url('game/change_class')?>">
                <p>Change Class</p>
                <i class='bx bx-refresh bx-sm'></i>
            </a>
        </div>
    </div>
    <div class="playBtn">
        <a class="btn play-btn" href="<?=base_url('')?>">Play</a>
    </div>
</div>