<h1>MANAGE ACCOUNT</h1>
<?=validation_list_errors(); ?>
<div>
    <form id="login_form" action="<?=base_url('user/update')?>" method="post">
        <input type="hidden" name="_method" value="PUT">
        <label for="username">Username</label><br />
        <input id="username" type="text" name="username" value="<?= old('username') ?>"><br />
        <input id="submit_form" type="submit" value="Update profile">
        <p class="alert"></p>
    </form>
    <?php print_r($userInfo)?>
    <a href="<?=base_url('user/change_password')?>">Change password</a>
    <?php if(session('login_error')): ?>
    <p class="alert"><?=session('login_error');?></p>
    <?php endif; ?>
</div>