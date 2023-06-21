<div>
    <h1>Manage Publications</h1>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Publication</th>
                    <th>Name</th>
                    <th>Desciption</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($classes) && count($classes) > 0): ?>
                    <?php foreach($classes as $class): ?>
                        <dialog data-characterId="<?=$class->id?>">
                        <form>
                            <p>Are you sure you want to delete '<?=$class->name?>'?</p>
                            <a href="<?=base_url('user/admin/classes/delete/'.$class->id); ?> " class="btn danger">YES</a>
                            <button formmethod="dialog">NO</button>
                        </form>
                        </dialog>
                        <tr>
                            <td><a href="<?=base_url("classes/".$class->name_compiled); ?>"><?="classes/".$class->name_compiled?></a></td>
                            <td><?=$class->name; ?></td>
                            <td><?=character_limiter($class->description, 30, '...'); ?></td>
                            <td>
                                <a href="<?=base_url('user/admin/classes/edit/'.$class->id); ?>" class="btn">Edit</a>
                                <button type="button" data-characterId="<?=$class->id?>" class="btn danger">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td>Nothing published yet.</td></tr>
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
        <form action="<?=base_url('user/admin/classes/'). ((isset($isPUT) && $isPUT) ? 'update' : 'create'); ?>" method="post" 
        <?=(isset($isPUT) && $isPUT)?"enctype='multipart/form-data'":"enctype='multipart/form-data'";?> class="form">
                <?php if(isset($isPUT) && $isPUT):?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif;?>
                <label for="name">Name</label><br />
                <input id="name" name="name" value="<?= $classInfo['name'] ?? old('name') ?>"/><br />
                
                <label for="description">Desciption</label><br />
                <textarea id="description" name="description"><?= $classInfo['description'] ?? old('description') ?></textarea><br />
                
                <label for="image">Image</label><br />
                <input id="image" name="image" type="file"/><br />

                <?php if(isset($isPUT) && $isPUT):?>
                <img src="<?=base_url('images/thumb/publishedClasses/').$classInfo['image_path']?>" alt="<?=$classInfo['name']?>" class="rounded p-4 mx-auto"><br />
                <?php endif;?>

                <input type="hidden" name="id" value="<?=$classInfo['id'] ?? null; ?>"/>
                <button id="submit" class="btn green" type="submit"><?=(isset($isPUT) && $isPUT)?"Save Changes":"Publish";?></button>
                <?php if(isset($isPUT) && $isPUT):?>
                    <a href="<?=base_url('user/admin/classes/manage')?>" class="btn">Cancel</a>
                <?php endif;?>
        </form>
    </div>
</div>