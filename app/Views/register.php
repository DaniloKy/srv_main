<h1>Register</h1>
<?=validation_list_errors(); ?>
<div>
    <form id="register_form" action="<?=base_url('register')?>" method="POST">
        <label for="username">Username</label><br />
        <input id="username" type="text" name="username" value="<?= old('username') ?>" placeholder="username"><br />
        <label for="email">Email</label><br />
        <input id="email" type="text" name="email" value="<?= old('email') ?>" placeholder="email"><br />
        <label for="password">Password</label><br />
        <input id="password" type="password" name="password" placeholder="password"><br />
        <label for="confirm_pass">Confirm password</label><br />
        <input id="confirm_pass" type="password" name="password_confirmation" placeholder="confirm password"><br />
        <input id="submit_form" type="submit" value="Register">
        <p class="alert"></p>
        <p>Already have an account! <a href="<?=base_url('login')?>">Login</a></p>
    </form>
    <?php if(session('register_error')): ?>
    <p class="alert"><?=session('register_error');?></p>
    <?php endif; ?>
</div>