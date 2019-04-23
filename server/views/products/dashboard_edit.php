<h1>Добавить товар</h1>
<?php if (Session::hasMessage()): ?>
    <div class="alert alert-<?= Session::messageType(); ?>">
        <?= Session::message(); ?>
    </div>
<?php endif; ?>
<form action="" method="post" enctype="multipart/form-data">
    <h3>Информция</h3>
    <div class="form-check mt-5">
        <input type="hidden" name="hide" value="0">
        <input type="checkbox" class="form-check-input" id="hide" name="hide" value="1"
               <?php if ($product['show'] == 0): ?>checked<?php endif; ?>>
        <label for="hide" class="form-check-label">Скрыть</label>
    </div>
    <div class="form-group row mt-5">
        <label for="name" class="col-md-1 col-form-label">Название</label>
        <div class="col-md-11">
            <input type="text" class="form-control" id="name" placeholder="Введите название" name="name" required
                   value="<?= $product['name']; ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="price" class="col-md-1 col-form-label">Цена</label>
        <div class="col-md-11">
            <input type="number" class="form-control" id="price" placeholder="Введите цену" name="price" min="0"
                   required value="<?= $product['price']; ?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="new-price" class="col-md-1 col-form-label">Цена по скидке</label>
        <div class="col-md-11">
            <input type="number" class="form-control" id="new-price" placeholder="Введите новую цену" name="new-price"
                   min="0" value="<?= $product['new_price']; ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="col" class="col-md-1 col-form-label">Количество на складе</label>
        <div class="col-md-11">
            <input type="number" class="form-control" id="col" placeholder="Введите количество" min="0"
                   name="col" required value="<?= $product['col']; ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="category" class="col-md-1 col-form-label">Категория</label>
        <div class="col-md-11">
            <select name="category" id="category" class="form-control">
                <?php if ($categories): ?>
                    <?php $oldCat = $product['category']; ?>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id']; ?>"
                                <?php if ($oldCat == $category['id']): ?>selected<?php endif; ?>><?= $category['name']; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="desc" class="col-md-1 col-form-label">Описание</label>
        <div class="col-md-11">
            <textarea name="desc" id="desc" class="form-control" placeholder="Описание товара"
                      rows="8"><?= $product['desc']; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="main-photo">Основное фото</label>
        <input type="file" name="main-photo" id="main-photo" class="form-control-file" accept="image/*">
    </div>
    <?php if (!$product['main_photo']) {
        $product['main_photo'] = Config::get('photo.default');
    } ?>
    <img src="<?= Config::get('storage.photo') . $product['main_photo']; ?>" alt="<?= $product['name']; ?>"
         class="product-photo-edit">
    <h3>Дополнительные фотографии</h3>
    <div class="product-photos-edit">
        <?php if ($photos): ?>
            <?php foreach ($photos as $photo): ?>
                <div class="d-inline-block text-center">
                    <img src="<?= Config::get('storage.photo') . $photo['photo']; ?>" alt="<?= $product['name']; ?>"
                         class="product-photo-edit-small">
                    <a href="#" class="delete-img d-block" data-id="<?= $photo['id']; ?>">Удалить</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div id="del-photos">

    </div>
    <div id="product-photos">
        <div class="form-group">
            <label for="photo-0">Фото 1</label>
            <input type="file" name="photos[]" id="photo-0" class="form-control-file" accept="image/*">
        </div>
    </div>
    <button class="btn btn-info btn-sm" id="add-photo" type="button" data-n="1">Добавить</button>
    <h3>Характеристики</h3>
    <datalist id="specs-list">
        <?php if ($allSpecs): ?>
            <?php foreach ($allSpecs as $spec): ?>
                <option value="<?= $spec['name']; ?>"></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </datalist>
    <div id="product-specs" class="form-group">
        <?php if ($specs): ?>
            <?php foreach ($specs as $spec): ?>
                <div class="product-spec row mt-1">
                    <div class="col">
                        <input type="text" name="spec-name[]" class="form-control col spec-name" list="specs-list"
                               placeholder="Имя" value="<?= $spec['name']; ?>">
                    </div>
                    <div class="col">
                        <input type="text" name="spec-value[]" class="form-control col" placeholder="Значение"
                               value="<?= $spec['value']; ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="product-spec row">
                <div class="col">
                    <input type="text" name="spec-name[]" class="form-control col spec-name" list="specs-list"
                           placeholder="Имя">
                </div>
                <div class="col">
                    <input type="text" name="spec-value[]" class="form-control col" placeholder="Значение">
                </div>
            </div>
        <?php endif; ?>
    </div>
    <button class="btn btn-info btn-sm" id="add-spec" type="button" data-n="1">Добавить</button>
    <div class="form-group mt-5">
        <input type="submit" class="btn btn-success" value="Сохранить">
    </div>
</form>
<script src="/scripts/product.js"></script>