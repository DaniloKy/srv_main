<div>
    <?//php dd($users);?>
    <h1>Users Dashboard</h1>
    <?php if(session('error')): ?>
            <p class="alert"><?=session('error');?></p>
        <?php endif; ?>
        <?php if(session('success')): ?>
            <p class="success"><?=session('success');?></p>
        <?php endif; ?>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                    <th>Super</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($users) && count($users) > 0): ?>
                    <?php foreach($users as $user): ?>
                        <dialog data-banId="<?=$user['id']?>" class="confirmation_box">
                            <p>Are you sure you want to <strong>ban</strong> "<?=$user['username']?>"?</p>
                            <form action="<?=base_url('user/admin/users/ban')?>" method="post">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id" value="<?=$user['id']?>">
                                <button type="submit" class="btn danger">YES</button>
                                <button formmethod="dialog" class="btn">NO</button>
                            </form>
                        </dialog>
                        <dialog data-makeSuperId="<?=$user['id']?>" class="confirmation_box">
                            <p>Are you sure you want to make "<?=$user['username']?>"? a <strong>super admin</strong></p>
                            <form action="<?=base_url('user/admin/users/makeSuper')?>" method="post">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id" value="<?=$user['id']?>">
                                <button type="submit" class="btn danger">YES</button>
                                <button formmethod="dialog" class="btn">NO</button>
                            </form>
                        </dialog>
                        <dialog data-unbanId="<?=$user['id']?>" class="confirmation_box">
                            <p>Are you sure you want to <strong>unban</strong> "<?=$user['username']?>"?</p>
                            <form action="<?=base_url('user/admin/users/removeBan')?>" method="post">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id" value="<?=$user['id']?>">
                                <button type="submit" class="btn danger">YES</button>
                                <button formmethod="dialog" class="btn">NO</button>
                            </form>
                        </dialog>
                        <dialog data-removeSuperId="<?=$user['id']?>" class="confirmation_box">
                            <p>Are you sure you want to <strong>remove</strong> "<?=$user['username']?>'s" <strong>super admin</strong>?</p>
                            <form action="<?=base_url('user/admin/users/removeSuper')?>" method="post">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id" value="<?=$user['id']?>">
                                <button type="submit" class="btn danger">YES</button>
                                <button formmethod="dialog" class="btn">NO</button>
                            </form>
                        </dialog>
                        <tr>
                            <td><?=$user['id']; ?></td>
                            <td><?=$user['username']; ?></td>
                            <td><?=$user['email']; ?></td>
                            <td>
                                <?php if($user['status'] == 1):?>
                                    <button type="button" data-banId="<?=$user['id']?>" class="btn danger">Ban</button>
                                <?php else:?>
                                    <button type="button" data-unbanId="<?=$user['id']?>" class="btn danger">Unban</button>
                                <?php endif;?>
                            </td>
                            <td>
                                <?php if($user['super'] == 0):?>
                                    <button type="button" data-makeSuperId="<?=$user['id']?>" class="btn special">Make super</button>
                                <?php else:?>
                                    <button type="button" data-removeSuperId="<?=$user['id']?>" class="btn special">Remove super</button>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td>Nothing published yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if(isset($pager)): ?>
            <div class="pagination">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>