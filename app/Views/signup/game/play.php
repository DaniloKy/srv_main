<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/images/potion-24.svg">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
        <link rel="stylesheet" href="/css/game_play.css">
        <script type="module" src="/js/game/play.js"></script>
        <title>Survive Utopia</title>
    </head>
    <body>
        <div class="container">
            <canvas id="game" class="visually-hidden"></canvas>
            <div class="UI">
                <div class="hpUI visually-hidden">
                    <div class="hp_txt">
                        <p>HP</p>
                        <p><span id="current_hp">100</span>/<span id="max_hp">100</span></p>
                    </div>
                    <progress id="hp_progress" class="hp-bar" value="100" max="100"></progress>
                </div>
                <div class="pointsTable visually-hidden">
                    <p><span id="game_status"></span></p>
                    <p><span id="player_kills"></span> - kills</p>
                    <p class="xp_gained">+<span id="xp_gained"></span>XP</p>

                    <a class="btn" href="<?=base_url('game/lobby')?>">Back to lobby</a>
                </div>
                <div id="users_list" class="visually-hidden">
                    <ul></ul>
                </div>
            </div>
            <div class="lists">
                <div id="queue_list">
                    <h3>Players In Queue </h3>
                    <ul></ul>
                </div>
                <div class="status">
                    <h2 class="game_status">Waiting for more players to join.</h2>
                    <a class="btn_queue" href="<?=base_url('game/lobby')?>">Go Back to lobby</a>
                </div>
                <div id="midGame_list">
                    <h3>Playing right now</h3>
                    <ul><p>No one playing</p></ul>
                </div>
            </div>
        </div>
        <input type="hidden" class="visually-hidden" name="username" value="<?=$playerInfo->username?>">
        <input type="hidden" class="visually-hidden" name="class_name" value="<?=$playerInfo->class_name?>">
        <input type="hidden" class="visually-hidden" name="level" value="<?=$playerInfo->level?>">
    </body>
</html>