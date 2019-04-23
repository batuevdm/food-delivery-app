<div class="block-name">
    Заказы
</div>
<div class="page-content">
    <?php if (Session::hasMessage()): ?>
        <div class="alert alert-<?= Session::messageType(); ?>">
            <?= Session::message(); ?>
        </div>
    <?php endif; ?>
    <?php if ($orders): ?>
        <table class="orders-container">
            <thead>
            <tr>
                <th>Дата</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= dateFormat($order['date']); ?></td>
                    <td><?= _p($order['sum']); ?> ₽</td>
                    <td><?= Config::get('order.status')[$order['status']] ?></td>
                    <td class="order-options">
                        <a href="/account/order/view/<?= $order['id']; ?>" class="btn">Открыть</a>
<!--                        <a href="/account/order/delete/--><?//= $order['id']; ?><!--" class="btn" onclick="return confirmDelete('удалить заказ')">Удалить</a>-->
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        Пока у Вас нет заказов
    <?php endif; ?>
</div>