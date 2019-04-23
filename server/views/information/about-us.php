<div class="block-name">
    О нас
</div>
<div class="page-content">
    <?= Config::get('site.desc'); ?> <?= Config::get('site.name'); ?>
    <br><br>
    Контакты:
    <div class="page-content">
        <div class="footer-contact icon-address"><?= Config::get('contact.address'); ?></div>
        <a href="tel:<?= Config::get('contact.phone'); ?>"
           class="footer-link footer-contact icon-phone"><?= Config::get('contact.phone'); ?></a>
        <a href="mailto:<?= Config::get('contact.email'); ?>"
           class="footer-link footer-contact icon-mail"><?= Config::get('contact.email'); ?></a>
    </div>
</div>