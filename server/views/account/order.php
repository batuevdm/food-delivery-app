<div class="block-name">
    Заказ #<?= $order['id']; ?>
</div>
<div class="page-content">
    <div class="order-information-container">
        <div class="order-information">Дата заказа: <?= dateFormat($order['date']); ?></div>
        <div class="order-information">Статус: <?= Config::get('order.status')[$order['status']]; ?></div>
        <div class="order-information">Общая сумма: <?= _p($orderSum); ?> ₽</div>
        <div class="order-information">Адрес доставки: <?= $address; ?></div>
    </div>

    <div class="order-products">
        <div class="block-name">Содержимое заказа</div>
        <div class="cart-products-container col-12">
            <?php foreach ($products as $product): ?>
            <div class="cart-product">
                <div class="row">
                    <div class="col-md-2 col-4">
                        <a href="/products/product/<?= $product['product']; ?>">
                            <img src="<?= $product['inf']['main_photo']; ?>" alt="<?= $product['inf']['name']; ?>" class="cart-product-photo">
                        </a>
                    </div>
                    <a href="/products/product/<?= $product['product']; ?>" class="cart-product-name col-md-7 col-8"><?= $product['inf']['name']; ?></a>
                    <div class="cart-product-options col-md-3 col-12">
                        <div class="cart-product-col order-col">
                            <?= _p($product['price']); ?> ₽ * <?= $product['col']; ?> шт.
                        </div>
                        <div class="cart-product-price"><?= _p($product['price'] * $product['col']); ?> ₽</div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>