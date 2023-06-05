<h1>Login</h1>
<?=validation_list_errors(); ?>
<div class="container">
    <form id="login_form" action="<?=base_url('logining')?>" method="POST">
        <label for="email">Email</label><br />
        <input id="email" type="text" placeholder="email" name="email" value="<?= old('email') ?>" required maxlength="120"><br />
        <label for="password">Password</label><br />
        <input id="password" type="password" placeholder="password" name="password" required maxlength="120"><br />
        <input type="checkbox" id="rememberMe"  name="checkbox"><label for="rememberMe">Remember me</label><br />
        <input id="submit_form" type="submit" value="Log in">
        <p class="alert"></p>
        <p>Don't have an account yet? <a href="<?=base_url('register')?>">Register</a></p>
    </form>
    <?php if(session('login_error')): ?>
    <p class="alert"><?=session('login_error');?></p>
    <?php endif; ?>
</div>