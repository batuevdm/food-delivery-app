<div class="block-name">Регистрация</div>
<div class="page-content">
    <form action="" method="post" class="login-container">
        <?php if(Session::hasMessage()): ?>
            <div class="alert alert-<?= Session::messageType(); ?>">
                <?= Session::message(); ?>
            </div>
        <?php endif; ?>
        <input type="text" class="login-field" name="ln" placeholder="Фамилия" required value="<?php Session::field('reg.ln'); ?>">
        <input type="text" class="login-field" name="fn" placeholder="Имя" required value="<?php Session::field('reg.fn'); ?>">
        <input type="text" class="login-field" name="mn" placeholder="Отчество (если есть)" value="<?php Session::field('reg.mn'); ?>">
        <hr>
        <input type="email" name="login" class="login-field" placeholder="E-mail" required value="<?php Session::field('reg.email'); ?>">
        <input type="password" name="password" class="login-field" placeholder="Пароль" required>
        <input type="password" name="password2" class="login-field" placeholder="Пароль еще раз" required>
        <button type="submit" class="login-btn">Регистрация</button>
        <a href="/account/login<?= $next; ?>" >Вход</a>
    </form>
</div>