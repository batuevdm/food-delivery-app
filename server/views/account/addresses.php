<div class="block-name">
    Адреса для доставки
</div>
<div class="page-content">
    <?php if (Session::hasMessage()): ?>
        <div class="alert alert-<?= Session::messageType(); ?>">
            <?= Session::message(); ?>
        </div>
    <?php endif; ?>
    <?php if ($isAdd): ?>
        <form action="/account/addresses/add" method="post" class="login-container">
            <textarea name="address" class="address-field" placeholder="Адрес" required></textarea>
            <button type="submit" class="login-btn">Добавить</button>
        </form>
    <?php else: ?>
    <?php if ($addresses): ?>
        <?php foreach ($addresses as $address): ?>
            <div class="address-block clearfix">
                <div class="address-name">
                    <?= $address['address']; ?>
                </div>
                <div class="address-options">
                    <a href="/account/addresses/delete/<?= $address['id']; ?>"
                       onclick="return confirmDelete('удалить адрес');">Удалить</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        Пока нет адресов
    <?php endif; ?>
    <a href="/account/addresses/add">Добавить новый</a>
    <?php endif; ?>
</div>