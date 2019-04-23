<div class="block-name">Аккаунт</div>
<div class="page-content">
    <div class="clearfix">
        <div class="account-name">
            <?= $inf['last_name']; ?> <?= $inf['first_name']; ?> <?= $inf['middle_name']; ?>
        </div>
        <div class="account-logout clearfix">
            <a href="/account/logout">Выход</a>
        </div>
    </div>
    <div class="account-information">
        <div class="information-block">
            <a href="/account/orders">Заказы:</a> <?= $ordersCol; ?>
        </div>
        <div class="information-block">
            <a href="/account/addresses">Адреса для доставки:</a> <?= $addressesCol; ?>
        </div>
    </div>
</div>