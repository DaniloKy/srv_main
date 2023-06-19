<h1>MANAGE ACCOUNT</h1>
<div>
    <?php foreach(validation_errors() as $error): ?>
        <p class="alert"><?=$error?></p>
    <?php endforeach; ?>
    <?php if(session('update_error')): ?>
    <p class="alert"><?=session('update_error');?></p>
    <?php endif; ?>
    <?php if(session('update_success')): ?>
    <p class="success"><?=session('update_success');?></p>
    <?php endif; ?>
    <form id="login_form" action="<?=base_url('user/update')?>" method="post" class="form">
        <input type="hidden" name="_method" value="PUT">
        <label for="username">Username</label><br />
        <input id="username" type="text" name="username" value="<?= $userInfo['username'] ?? old('username') ?>"><br />
        <button id="submit_form" class="btn green" type="submit">Update profile</button>
    </form>
    <a href="<?=base_url('user/change_password')?>">Change password</a>
    <?php if(session('login_error')): ?>
    <p class="alert"><?=session('login_error');?></p>
    <?php endif; ?>
</div>