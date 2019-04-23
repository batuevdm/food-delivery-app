<h1>Категории</h1>
<?php if(Session::hasMessage()): ?>
    <div class="alert alert-<?= Session::messageType(); ?>">
        <?= Session::message(); ?>
    </div>
<?php endif; ?>
<div class="form-group">
    <a href="/dashboard/categories/add" class="btn btn-success">Добавить</a>
</div>
<div class="table-responsive">
    <table class="table table-borderless">
        <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Категория</th>
            <th>Родительская категория</th>
            <th>Количество товаров</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($categories): ?>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= $category['id']; ?></td>
                    <td><?= $category['name']; ?></td>
                    <td><?= $category['parent'] ? $category['parent'] : 'Нет'; ?></td>
                    <td><?= $category['col']; ?></td>
                    <td><a role="button" class="btn btn-info" href="/dashboard/categories/edit/<?= $category['id']; ?>">Изменить</a>
                    </td>
                    <td><a role="button" class="btn btn-danger"
                           href="/dashboard/categories/delete/<?= $category['id']; ?>" onclick="return confirmDelete('удалить эту категорию');">Удалить</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Нет категорий</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>