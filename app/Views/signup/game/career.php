<div>
    <h1>Player Career</h1>
    <div class="stats_container">
        <div class="stats">
            <h2>Games</h2>
            <div class="stats_info">
                <h3>Games Played <span class="stats_value"><?=$playerInfo->games_played?></span></h3>
                <h3>Games Won <span class="stats_value"><?=$playerInfo->games_won?></span></h3>
                <h3>Games Lost <span class="stats_value"><?=$playerInfo->games_lost?></span></h3>
                <h3>W/L <span class="stats_value"><?=$playerInfo->wl?></span></h3>
            </div>
        </div>
        <div class="stats">
            <h2>Player</h2>
            <div class="stats_info">
                <h3>Kills <span class="stats_value"><?=$playerInfo->kills?></span></h3>
                <h3>Deaths <span class="stats_value"><?=$playerInfo->deaths?></span></h3>
                <h3>K/D <span class="stats_value"><?=$playerInfo->kd?></span></h3>
            </div>
        </div>
    </div>
</div>