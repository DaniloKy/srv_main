<h1>Login</h1>
<div>
    <?php foreach(validation_errors() as $error): ?>
        <p class="alert"><?=$error?></p>
    <?php endforeach; ?>
    <?php if(session('login_error')): ?>
    <p class="alert"><?=session('login_error');?></p>
    <?php endif; ?>
    <form id="login_form" action="<?=base_url('login')?>" method="POST" class="form">
        <label for="login_input">Username or email address</label><br />
        <input id="login_input" type="text" name="login_input" value="<?= old('login_input') ?>"><br />
        <label for="password">Password</label><br />
        <input id="password" type="password" name="password"><br />
        <input type="checkbox" id="rememberMe"  name="rememberMe"><label for="rememberMe">Remember me</label><br />
        <Button id="submit_form" type="submit" class="btn green">Log in</Button><br />
        <a href="<?=base_url('password_reset')?>">Forgot password?</a>
    </form>
    <p>Don't have an account yet?<a href="<?=base_url('register')?>">Register</a></p>
    
</div>