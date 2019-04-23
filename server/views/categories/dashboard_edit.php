<h1>Изменение категории</h1>
<form action="" method="post" enctype="multipart/form-data">
    <?php if(Session::hasMessage()): ?>
        <div class="alert alert-<?= Session::messageType(); ?>">
            <?= Session::message(); ?>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <label for="category">Название</label>
        <input type="text" class="form-control" id="category" name="name" value="<?= $category['name']; ?>" required>
        <input type="hidden" name="id" value="<?= $category['id']; ?>" required>
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
    <?php if (!$category['image']) {
        $category['image'] = Config::get('photo.default');
    } ?>
    <img src="<?= Config::get('storage.photo') . $category['image']; ?>" alt="<?= $category['name']; ?>"
         class="product-photo-edit">
    <div class="form-group">
        <input type="submit" class="btn btn-info" value="Изменить">
    </div>
</form>