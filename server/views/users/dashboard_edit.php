<h1>Добавление пользователя</h1>
<form action="" method="post" class="login-container">
    <?php if (Session::hasMessage()): ?>
        <div class="alert alert-<?= Session::messageType(); ?>">
            <?= Session::message(); ?>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <input type="text" class="form-control" name="ln" placeholder="Фамилия" required
               value="<?= $user['last_name']; ?>">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="fn" placeholder="Имя" required
               value="<?= $user['first_name']; ?>">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="mn" placeholder="Отчество (если есть)"
               value="<?= $user['middle_name']; ?>">
    </div>
    <hr>
    <div class="form-group">
        <input type="email" name="login" class="form-control" placeholder="E-mail" required
               value="<?= $user['email']; ?>">
    </div>
    <button type="submit" class="btn btn-success">Сохранить</button>
</form>