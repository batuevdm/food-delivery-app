<div class="block-name">Заказ</div>
<div class="page-content">
    Товары:
    <div class="order-products">
        <?php if ($products): ?>
            <?php foreach ($products as $product): ?>
                <div class="order-product">
                    <?= $product['product']['name']; ?>: <?= _p($product['product']['price']); ?> ₽ * <?= $product['col']; ?> = <?= _p($product['product']['price'] * $product['col']); ?> ₽
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="total-price">Оплата: <?= _p($totalPrice); ?> ₽</div>
    Оплата производится при получении товара. Доставка осуществляется курьером или почтой.
    <br><br>
    <div class="block-name">Данные</div>
    <div class="page-content">
        <?php if (Session::hasMessage()): ?>
            <div class="alert alert-<?= Session::messageType(); ?>">
                <?= Session::message(); ?>
            </div>
        <?php endif; ?>
        <form action="" method="post" class="login-container login-container-left">
            <label for="ln">Фамилия</label>
            <input id="ln" type="text" class="login-field" name="ln" placeholder="Фамилия" value="<?= $user['last_name']; ?>" readonly>
            <label for="fn">Имя</label>
            <input id="fn" type="text" class="login-field" name="fn" placeholder="Имя" value="<?= $user['first_name']; ?>" readonly>
            <label for="mn">Отчество</label>
            <input id="mn" type="text" class="login-field" name="mn" placeholder="Отчество" value="<?= $user['middle_name']; ?>" readonly>
            <hr>
            <label for="order-address">Адрес доставки</label>
            <select name="address" id="order-address" class="login-field">
                <?php $field = Session::field('order.address', true); ?>
                <?php if ($addresses): ?>
                    <?php foreach ($addresses as $address): ?>
                        <option value="<?= $address['id'] ?>" <?php if ($field == $address['id']): ?>selected<?php endif; ?>><?= $address['address'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
                <option value="new" id="new-address-option" <?php if ($field == 'new'): ?>selected<?php endif; ?>>Новый</option>
            </select>
            <textarea name="address-field" class="address-field" id="order-address-field" placeholder="Адрес"><?php Session::field('order.address.field') ?></textarea>
            <button type="submit" class="login-btn">Подтвердить</button>
        </form>
    </div>
</div>