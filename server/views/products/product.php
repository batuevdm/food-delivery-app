<div class="container no-padding">
    <div class="row center-sm">
        <div class="col-md-5">
            <?php if ($product['main_photo'] != Config::get('photo.default')): ?>
                <a href="<?= $product['main_photo']; ?>" data-fancybox="product">
                	<div class="product">
                		<div class="image">
	                		<img src="<?= $product['main_photo']; ?>" alt="<?= $product['name']; ?>">
	                	</div>
                	</div>
                </a>
            <?php else: ?>
                <img src="<?= $product['main_photo']; ?>" alt="<?= $product['name']; ?>" class="main-product-photo">
            <?php endif; ?>
            <?php if ($photos): ?>
                <div class="carousel-container">
                    <div class="carousel-button" id="carousel-button-left">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    <div class="owl-carousel other-photos-carousel">
                        <?php foreach ($photos as $photo): ?>
                            <a href="<?= Config::get('storage.photo') . $photo['photo']; ?>" data-fancybox="product"><img
                                        src="<?= Config::get('storage.photo') . $photo['photo']; ?>" alt=""></a>
                        <?php endforeach; ?>
                    </div>
                    <div class="carousel-button" id="carousel-button-right">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                <?php if (count($photos) < 5): ?>
                    <style scoped>
                        .carousel-button {
                            visibility: hidden;
                        }
                    </style>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="col-md-7">
            <div class="main-product-name">
                <?= $product['name']; ?>
            </div>
            <div class="main-product-price">
                <?php if ($product['new_price'] !== null): ?>
                    <span class="new-price"><?= _p($product['new_price']); ?></span>
                    <span class="old-price"><?= _p($product['price']); ?></span>
                <?php else: ?>
                    <?= _p($product['price']); ?>
                <?php endif; ?> ₽
            </div>
            <?php if ($product['col'] > 0): ?>
                <div class="main-product-text">
                    Количество:
                </div>
                <div class="main-product-options">
                    <input type="number" name="col" id="col" class="main-product-col" min="1"
                           max="<?= $product['col']; ?>" value="1">
                    <a class="main-add-to-cart" role="button" data-id="<?= $product['id']; ?>">Добавить в корзину</a>
                </div>
                <div class="main-product-text">
                    В наличии: <?= $product['col']; ?> шт.
                </div>
            <?php else: ?>
                <div class="main-product-text">
                    Нет в наличии
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row description">
        <div class="col-12">
            <div class="block-name">
                Описание
            </div>
            <div class="product-description">
                <?= nl2br($product['desc']); ?>
            </div>
        </div>
    </div>
    <?php if ($specs): ?>
        <div class="row specs">
            <div class="col-12">
                <div class="block-name">
                    Характеристики
                </div>
                <div class="product-specs">
                    <div class="specs-table">
                        <?php foreach ($specs as $spec): ?>
                            <div class="table-row">
                                <div class="row-name"><?= $spec['name']; ?></div>
                                <div class="row-value"><?= $spec['value']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <hr>
    <div class="row breadcrumbs">
        <div class="col-12">
            <a href="/">Главная</a> <?php foreach ($breadcrumbs as $item): ?> > <a
                    href="/products/category/<?= $item[0]; ?>"><?= $item[1]; ?></a> <?php endforeach; ?>
            > <?= $product['name']; ?>
        </div>
    </div>
</div>
<style>
	.product:hover {
		filter: none;
	}
</style>