<h1>Заказы</h1>
<?php if (Session::hasMessage()): ?>
    <div class="alert alert-<?= Session::messageType(); ?>">
        <?= Session::message(); ?>
    </div>
<?php endif; ?>
<div class="table-responsive">
    <table class="table table-borderless">
        <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Имя</th>
            <th>Дата</th>
            <th>Сумма</th>
            <th>Статус</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($orders): ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id']; ?></td>
                    <td><?= $order['name']; ?></td>
                    <td><?= dateFormat($order['date']); ?></td>
                    <td><?= _p($order['sum']); ?> ₽</td>
                    <td><?= $order['status']; ?></td>
                    <td><a role="button" class="btn btn-info" href="/dashboard/orders/order/<?= $order['id']; ?>">Просмотр</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Нет заказов</td>
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
                    <a class="page-link" href="/dashboard/users/view/<?= $pg['current'] - 1; ?>">&laquo;</a>
                </li>
            <?php endif; ?>
            <?php if ($pg['start'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="/dashboard/users/view/1">1</a>
                </li>
                <?php if ($pg['start'] > 2): ?>
                    <li class="page-item">
                        <a class="page-link">...</a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
            <?php for ($i = $pg['start']; $i <= $pg['end']; $i++): ?>
                <li class="page-item<?php if ($i == $pg['current']): ?> active<?php endif; ?>">
                    <a href="/dashboard/users/view/<?= $i; ?>" class="page-link"><?= $i; ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($pg['end'] < $pg['count']): ?>
                <?php if ($pg['end'] < $pg['count'] - 1): ?>
                    <li class="page-item">
                        <a class="page-link">...</a>
                    </li>
                <?php endif; ?>
                <li class="page-item">
                    <a class="page-link" href="/dashboard/users/view/<?= $pg['count']; ?>"><?= $pg['count']; ?></a>
                </li>
            <?php endif; ?>
            <?php if ($pg['current'] < $pg['count']): ?>
                <li class="page-item">
                    <a class="page-link" href="/dashboard/users/view/<?= $pg['current'] + 1; ?>">&raquo;</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>