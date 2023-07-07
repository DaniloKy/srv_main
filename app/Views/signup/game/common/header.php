<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/images/potion-24.svg">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
        <link rel="stylesheet" href="/css/game.css">
        <?php if(esc(isset($cssPath))): ?>
            <link rel="stylesheet" href="/<?=esc($cssPath)?>">
        <?php endif; ?>
        <script src="/js/gameScript.js"></script>
        <?php if(esc(isset($jsPath['type'])) && esc(isset($jsPath['script'])) ): ?>
            <script type="<?=esc($jsPath['type'])?>" src="/<?=esc($jsPath['script'])?>"></script>
        <?php endif; ?>
        <title><?=esc($title)?></title>
    </head>
    <body>