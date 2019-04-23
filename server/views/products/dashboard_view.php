<h1>Товары</h1>
<?php if (Session::hasMessage()): ?>
    <div class="alert alert-<?= Session::messageType(); ?>">
        <?= Session::message(); ?>
    </div>
<?php endif; ?>
<div class="form-group">
    <a href="/dashboard/products/add" class="btn btn-success">Добавить</a>
</div>
<div class="table-responsive">
    <table class="table table-borderless">
        <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Цена</th>
            <th>Количество</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($products): ?>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['id']; ?></td>
                    <td><?= $product['name']; ?></td>
                    <td><?= $product['category']; ?></td>
                    <td><?php if (isset($product['new_price'])): ?><?= $product['new_price']; ?> <span
                                class="old-price"><?= $product['price']; ?></span><?php else: ?><?= $product['price']; ?><?php endif; ?>
                        ₽
                    </td>
                    <td><?= $product['col']; ?></td>
                    <td><a role="button" class="btn btn-info" href="/dashboard/products/edit/<?= $product['id']; ?>">Изменить</a>
                    </td>
                    <td><a role="button" class="btn btn-danger"
                           href="/dashboard/products/delete/<?= $product['id']; ?>"
                           onclick="return confirmDelete('удалить этот товар');">Удалить</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Нет товаров</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php if ($pg['count'] > 1): ?>
    <hr>
    <nav aria-label="pagination">
        <ul class="pagination pagination justify-content-center">
            <?php if ($pg['current'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="/dashboard/products/view/<?= $pg['current'] - 1; ?>">&laquo;</a>
                </li>
            <?php endif; ?>
            <?php if ($pg['start'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="/dashboard/products/view/1">1</a>
                </li>
                <?php if ($pg['start'] > 2): ?>
                    <li class="page-item">
                        <a class="page-link">...</a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
            <?php for ($i = $pg['start']; $i <= $pg['end']; $i++): ?>
                <li class="page-item<?php if ($i == $pg['current']): ?> active<?php endif; ?>">
                    <a href="/dashboard/products/view/<?= $i; ?>" class="page-link"><?= $i; ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($pg['end'] < $pg['count']): ?>
                <?php if ($pg['end'] < $pg['count'] - 1): ?>
                    <li class="page-item">
                        <a class="page-link">...</a>
                    </li>
                <?php endif; ?>
                <li class="page-item">
                    <a class="page-link" href="/dashboard/products/view/<?= $pg['count']; ?>"><?= $pg['count']; ?></a>
                </li>
            <?php endif; ?>
            <?php if ($pg['current'] < $pg['count']): ?>
                <li class="page-item">
                    <a class="page-link" href="/dashboard/products/view/<?= $pg['current'] + 1; ?>">&raquo;</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>