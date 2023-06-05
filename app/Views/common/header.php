<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php if(esc($cssPath != "")): ?>
            <link rel="stylesheet" href="/<?=esc($cssPath)?>">
        <?php endif; ?>
        <?php if(esc($jsPath != "")): ?>
            <script src="/<?=esc($jsPath)?>"></script>
        <?php endif; ?>
        <title><?=esc($title)?></title>
    </head>
    <body>
        <main>