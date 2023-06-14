<h1>Login</h1>
<?=validation_list_errors(); ?>
<div>
    <form id="login_form" action="<?=base_url('login')?>" method="POST">
        <label for="login_input">Username or email address</label><br />
        <input id="login_input" type="text" name="login_input" value="<?= old('login_input') ?>"><br />
        <label for="password">Password</label><br />
        <input id="password" type="password" name="password"><br />
        <input type="checkbox" id="rememberMe"  name="rememberMe"><label for="rememberMe">Remember me</label><br />
        <input id="submit_form" type="submit" value="Log in">
        <p class="alert"></p>
        <p>Don't have an account yet? <a href="<?=base_url('register')?>">Register</a></p>
    </form>
    <?php if(session('login_error')): ?>
    <p class="alert"><?=session('login_error');?></p>
    <?php endif; ?>
</div>