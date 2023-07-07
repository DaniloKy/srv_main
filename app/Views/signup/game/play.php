<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/images/potion-24.png">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
        <link rel="stylesheet" href="/css/game_play.css">
        <script type="module" src="/js/game/play.js"></script>
        <title>Survive Utopia</title>
    </head>
    <body>
        <div class="container">
            <canvas id="game"></canvas>
            <div id="users_list" class="hide">
                <ul></ul>
            </div>
        </div>
        <input type="hidden" class="visually-hidden" name="username" value="<?=$playerInfo->username?>">
        <input type="hidden" class="visually-hidden" name="class_name" value="<?=$playerInfo->class_name?>">
        <input type="hidden" class="visually-hidden" name="level" value="<?=$playerInfo->level?>">
    </body>
</html>