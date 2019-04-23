<div class="block-name">Новые товары</div>
<div class="page-content">
    <?php if ($products): ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="product">
                        <a href="/products/product/<?= $product['id']; ?>">
                            <div class="image">
                                <img src="<?= $product['main_photo']; ?>" alt="<?= $product['name']; ?>">
                            </div>
                            <div class="to-cart" data-id="<?= $product['id']; ?>">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="product-name"><?= $product['name']; ?></div>
                            <div class="product-price">
                                <?php if ($product['new_price'] !== null): ?>
                                    <span class="old-price"><?= _p($product['price']); ?></span>
                                    <?= _p($product['new_price']); ?>
                                <?php else: ?>
                                    <?= _p($product['price']); ?>
                                <?php endif; ?> ₽
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        Пока ничего нет
    <?php endif; ?>
</div>
<div style="display: none;" id="hidden-content">
    <div style="color: black;">
        <h2>Спасибо!</h2>
        <p>Заказ успешно оформлен, теперь его можно отслеживать в личном кабинете.</p>
    </div>
</div>
<?php if (Session::field('order.success', true)): ?>
    <script>
        window.onload = function () {
            $.fancybox.open({
                src  : '#hidden-content',
                type : 'inline',
                opts : {}
            });
        }
    </script>
<?php endif; ?>
