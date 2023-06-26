<div>
    <h1>Tags Dashboard</h1>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Tag</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($tags) && count($tags) > 0): ?>
                    <?php foreach($tags as $tag): ?>
                        <dialog data-characterId="<?=$tag->id?>" class="confirmation_box">
                            <p>Are you sure you want to delete '<?=$tag->tag?>'?</p>
                            <form action="<?=base_url('user/admin/tags/delete')?>" method="post">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="id" value="<?=$tag->id?>">
                                <button type="submit" class="btn danger">YES</button>
                                <button formmethod="dialog" class="btn">NO</button>
                            </form>
                        </dialog>
                        <tr>
                            <td><?=$tag->tag; ?></td>
                            <td>
                                <button type="button" data-characterId="<?=$tag->id?>" class="btn danger">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td>No tags yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div>
        <?php foreach(validation_errors() as $error): ?>
            <p class="alert"><?=$error?></p>
        <?php endforeach; ?>
        <?php if(session('error')): ?>
            <p class="alert"><?=session('error');?></p>
        <?php endif; ?>
        <?php if(session('success')): ?>
            <p class="success"><?=session('success');?></p>
        <?php endif; ?> 
        <form action="<?=base_url('user/admin/tags/create');?>" method="post" enctype='multipart/form-data' class="form">
                <label for="tag">Tag</label><br />
                <input id="tag" name="tag" value="<?= $annInfo['tag'] ?? old('tag') ?>"/><br />
                <a href="<?=base_url('user/admin/announcements/manage');?>" class="btn">Back</a>
                <button id="submit" class="btn green" type="submit">Create</button>
        </form>
    </div>
</div>