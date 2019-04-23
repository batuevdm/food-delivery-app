<div class="block-name">
    Корзина
</div>
<div class="page-content">
    <div class="row">
        <div class="cart-products-container col-12">
            <?php if ($products): ?>
                <?php foreach ($products as $product): ?>
                    <div class="cart-product" id="product-<?= $product['product']['id'] ?>">
                        <div class="row">
                            <div class="col-md-2 col-4">
                                <img src="<?= $product['product']['main_photo'] ?>" alt="" class="cart-product-photo">
                            </div>
                            <a href="/products/product/<?= $product['product']['id'] ?>" class="cart-product-name col-md-7 col-8"><?= $product['product']['name'] ?></a>
                            <div class="cart-product-options col-md-3 col-12">
                                <div class="cart-product-delete" role="button" data-id="<?= $product['product']['id']; ?>"></div>
                                <div class="cart-product-price">
                                    <?php if ($product['product']['new_price'] !== null): ?>
                                        <span class="old-price"><?= _p($product['product']['price']); ?></span>
                                        <?= _p($product['product']['new_price']); ?>
                                    <?php else: ?>
                                        <?= _p($product['product']['price']); ?>
                                    <?php endif; ?> ₽
                                </div>
                                <div class="cart-product-col">
                                    <div class="product-minus" role="button" data-id="<?= $product['product']['id']; ?>"></div>
                                    <input type="number" class="product-col" data-id="<?= $product['product']['id']; ?>" id="col-<?= $product['product']['id']; ?>" min="1" max="<?= $product['product']['col'] ?>" value="<?= $product['col']; ?>">
                                    <div class="product-plus" role="button" data-id="<?= $product['product']['id']; ?>"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                В Вашей корзине пока ничего нет
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="cart-total-price">
                Итоговая цена: <span id="totalPrice"><?= _p($totalPrice) ?></span> ₽
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="cart-buttons">
                <a href="/" class="cart-button cart-button-1" role="button">Продолжить покупки</a>
                <?php if ($isLogged): ?>
                    <a href="/cart/order" class="cart-button cart-button-2" role="button">Оформить заказ</a>
                <?php else: ?>
                    <a href="account/login/?next=/cart/order" class="cart-button cart-button-2" role="button">Оформить заказ</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>