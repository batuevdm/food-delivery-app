<h1>Добавление пользователя</h1>
<form action="" method="post" class="login-container">
    <?php if (Session::hasMessage()): ?>
        <div class="alert alert-<?= Session::messageType(); ?>">
            <?= Session::message(); ?>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <input type="text" class="form-control" name="ln" placeholder="Фамилия" required
               value="<?php Session::field('reg.ln'); ?>">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="fn" placeholder="Имя" required
               value="<?php Session::field('reg.fn'); ?>">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="mn" placeholder="Отчество (если есть)"
               value="<?php Session::field('reg.mn'); ?>">
    </div>
    <hr>
    <div class="form-group">
        <input type="email" name="login" class="form-control" placeholder="E-mail" required
               value="<?php Session::field('reg.email'); ?>">
    </div>
    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="Пароль" required>
    </div>
    <div class="form-group">
        <input type="password" name="password2" class="form-control" placeholder="Пароль еще раз" required>
    </div>
    <button type="submit" class="btn btn-success">Регистрация</button>
</form>