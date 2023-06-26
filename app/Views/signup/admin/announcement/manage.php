<div>
    <h1>Announcements Dashboard</h1>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Announcement</th>
                    <th>Title</th>
                    <th>Desciption</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($announcements) && count($announcements) > 0): ?>
                    <?php foreach($announcements as $ann): ?>
                        <dialog data-characterId="<?=$ann->id?>" class="confirmation_box">
                            <p>Are you sure you want to delete '<?=$ann->title?>'?</p>
                            <form action="<?=base_url('user/admin/announcements/delete')?>" method="post">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="id" value="<?=$ann->id?>">
                                <button type="submit" class="btn danger">YES</button>
                                <button formmethod="dialog" class="btn">NO</button>
                            </form>
                        </dialog>
                        <tr>
                            <td><a href="<?=base_url("announcements/".$ann->title_compiled); ?>"><?="announcements/".$ann->title_compiled?></a></td>
                            <td><?=$ann->title; ?></td>
                            <td><?=character_limiter($ann->description, 30, '...'); ?></td>
                            <td><?=$ann->created_by; ?></td>
                            <td><?=$ann->created_at; ?></td>
                            <td>
                                <a href="<?=base_url('user/admin/announcements/edit/'.$ann->id); ?>" class="btn">Edit</a>
                                <button type="button" data-characterId="<?=$ann->id?>" class="btn danger">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td>No announcements yet.</td></tr>
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
        <form action="<?=base_url('user/admin/announcements/'). ((isset($isPUT) && $isPUT) ? 'update' : 'create'); ?>" method="post" enctype='multipart/form-data' class="form">
                <?php if(isset($isPUT) && $isPUT):?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif;?>
                <label for="title">Title</label><br />
                <input id="title" name="title" value="<?= $annInfo['title'] ?? old('title') ?>"/><br />
                
                <label for="description">Desciption</label><br />
                <textarea id="description" name="description"><?= $annInfo['description'] ?? old('description') ?></textarea><br />
                
                <label for="image">Image</label><br />
                <input id="image" name="image" type="file"/><br />
                <fieldset class="form">
                    <legend>Add Tags</legend>
                    <?php if(isset($tags) && count($tags) > 0): ?>
                        <?php foreach($tags as $tag): ?>
                            <label for="<?=$tag->id?>"><?=$tag->tag?></label>
                            <input type="radio" id="<?=$tag->id?>" name="tag_id" value="<?=$tag->id?>" <?=(isset($annInfo) && $annInfo['tag_id'] == $tag->id)?'checked':'';?>><br />
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No tags</p>
                    <?php endif; ?>
                    <a href="<?=base_url('user/admin/tags/manage');?>">Create tags</a>
                </fieldset>
                <?php if(isset($isPUT) && $isPUT && isset($annInfo['image_path'])):?>
                <img src="<?=base_url('images/thumb/announcements/').$annInfo['image_path']?>" alt="<?=$annInfo['title']?>" class="rounded p-4 mx-auto"><br />
                <?php endif;?>
                <input type="hidden" name="id" value="<?=$annInfo['id'] ?? null; ?>"/>
                <button id="submit" class="btn green" type="submit"><?=(isset($isPUT) && $isPUT)?"Save Changes":"Publish";?></button>
                <?php if(isset($isPUT) && $isPUT):?>
                    <a href="<?=base_url('user/admin/announcements/manage')?>" class="btn">Cancel</a>
                <?php endif;?>
        </form>
    </div>
</div>