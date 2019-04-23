<div class="block-name"><?= $category; ?></div>
<div class="page-content">
    <?php if ($subcategories): ?>
        <div class="row">
            <?php foreach ($subcategories as $subcategory): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="product">
                        <a href="/products/category/<?= $subcategory['id']; ?>">
                            <div class="image">
                                <img src="<?= $subcategory['image']; ?>" alt="<?= $subcategory['name']; ?>">
                            </div>
                            <div class="subcategory-name"><?= $subcategory['name']; ?></div>
                            <div
                                class="subcategory-col"><?= $subcategory['col']; ?> <?= num2word($subcategory['col'], ['товар', 'товара', 'товаров']); ?></div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
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
            <?php if ($pg['count'] > 1): ?>
                <hr>
                <div class="row">
                    <div class="col-12 center">
                        <div class="pagination">
                            <?php if ($pg['current'] > 1): ?>
                                <a href="/products/category/<?= $categoryId; ?>/<?= $pg['current'] - 1; ?>">&laquo;</a>
                            <?php endif; ?>
                            <?php if ($pg['start'] > 1): ?>
                                <a href="/products/category/<?= $categoryId; ?>/1">1</a>
                                <?php if ($pg['start'] > 2): ?>
                                    <a>...</a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php for ($i = $pg['start']; $i <= $pg['end']; $i++): ?>
                                <a href="/products/category/<?= $categoryId; ?>/<?= $i; ?>"
                                   <?php if ($i == $pg['current']): ?>class="active"<?php endif; ?>><?= $i; ?></a>
                            <?php endfor; ?>
                            <?php if ($pg['end'] < $pg['count']): ?>
                                <?php if ($pg['end'] < $pg['count'] - 1): ?>
                                    <a>...</a>
                                <?php endif; ?>
                                <a href="/products/category/<?= $categoryId; ?>/<?= $pg['count']; ?>"><?= $pg['count']; ?></a>
                            <?php endif; ?>
                            <?php if ($pg['current'] < $pg['count']): ?>
                                <a href="/products/category/<?= $categoryId; ?>/<?= $pg['current'] + 1; ?>">&raquo;</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            Пока ничего нет
        <?php endif; ?>
    <?php endif; ?>
    <hr>
    <div class="row breadcrumbs">
        <div class="col-12">
            <a href="/">Главная</a> <?php foreach ($breadcrumbs as $item): ?> > <a
                href="/products/category/<?= $item[0]; ?>"><?= $item[1]; ?></a> <?php endforeach; ?> > <?= $category; ?>
        </div>
    </div>
</div>