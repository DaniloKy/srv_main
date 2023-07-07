<h1>MANAGE ACCOUNT</h1>
<h2>Change Password</h2>
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
    <form action="<?=base_url('user/update_password')?>" method="post" class="form">
        <h3><?=$userInfo['username']; ?></h3>
        <input type="hidden" name="_method" value="PUT">
        <label for="current_password">Current password</label><br />
        <input id="current_password" type="password" name="current_password" placeholder="Current password"><br />
        <label for="new_password">New Password</label><br />
        <input id="new_password" type="password" name="new_password" placeholder="New password"><br />
        <label for="new_password_confirmation">New password confirmation</label><br />
        <input id="new_password_confirmation" type="password" name="new_password_confirmation" placeholder="Password Confirmation"><br />
        <button id="submit_form" class="btn green" type="submit">Update profile</button>
    </form>
    <div class="logout">
        <a href="<?=base_url('user/manage');?>" class="btn">Back</a>
    </div>
</div>