<h1>Register</h1>
<div>
    <?php foreach(validation_errors() as $error): ?>
        <p class="alert"><?=$error?></p>
    <?php endforeach; ?>
    <?php if(session('register_error')): ?>
    <p class="alert"><?=session('register_error');?></p>
    <?php endif; ?>
    <form id="register_form" action="<?=base_url('register')?>" method="POST" class="form">
        <label for="username">Username</label><br />
        <input id="username" type="text" name="username" value="<?= old('username') ?>" placeholder="username"><br />
        <label for="email">Email</label><br />
        <input id="email" type="text" name="email" value="<?= old('email') ?>" placeholder="email"><br />
        <label for="password">Password</label><br />
        <input id="password" type="password" name="password" placeholder="password"><br />
        <label for="confirm_pass">Confirm password</label><br />
        <input id="confirm_pass" type="password" name="password_confirmation" placeholder="confirm password"><br />
        <Button id="submit_form" type="submit" class="btn green">Register</Button>
        <p>Already have an account! <a href="<?=base_url('login')?>">Login</a></p>
    </form>
</div>