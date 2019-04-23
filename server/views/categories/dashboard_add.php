<h1>Изменение категории</h1>
<?php if(Session::hasMessage()): ?>
    <div class="alert alert-<?= Session::messageType(); ?>">
        <?= Session::message(); ?>
    </div>
<?php endif; ?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="category">Название</label>
        <input type="text" class="form-control" id="category" name="name" value="">
    </div>
    <div class="form-group">
        <label for="parent">Родительская категория</label>
        <select name="parent" id="parent" class="form-control">
            <option value="-1">Нет</option>
            <?php if ($parents): ?>
                <?php foreach ($parents as $parent): ?>
                    <?php if ($parent['id'] != $category['id']): ?>
                        <option value="<?= $parent['id']; ?>" <?php if ($parent['id'] == $category['parent']): ?>selected<?php endif; ?>><?= $parent['name']; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="main-photo">Изображение</label>
        <input type="file" name="image" id="main-photo" class="form-control-file" accept="image/*">
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-info" value="Добавить">
    </div>
</form>