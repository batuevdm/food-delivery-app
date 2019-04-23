<div class="block-name">Вход</div>
<div class="page-content">
    <form action="" method="post" class="login-container">
        <?php if(Session::hasMessage()): ?>
            <div class="alert alert-<?= Session::messageType(); ?>">
                <?= Session::message(); ?>
            </div>
        <?php endif; ?>
        <input type="email" name="login" class="login-field" placeholder="E-mail" required value="<?php Session::field('login.email'); ?>">
        <input type="password" name="password" class="login-field" placeholder="Пароль" required>
        <button type="submit" class="login-btn">Войти</button>
        <a href="/account/register<?= $next; ?>" >Регистрация</a>
    </form>
</div>