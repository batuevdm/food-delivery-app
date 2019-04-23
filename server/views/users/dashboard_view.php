<h1>Пользователи</h1>
<?php if (Session::hasMessage()): ?>
    <div class="alert alert-<?= Session::messageType(); ?>">
        <?= Session::message(); ?>
    </div>
<?php endif; ?>
<div class="form-group">
    <a href="/dashboard/users/add" class="btn btn-success">Добавить</a>
</div>
<div class="table-responsive">
    <table class="table table-borderless">
        <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>ФИО</th>
            <th>E-Mail</th>
            <th>Должность</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($users): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id']; ?></td>
                    <td><?= $user['last_name'] . ' ' . $user['first_name'] . ' ' . $user['middle_name']; ?></td>
                    <td><a href="mailto:<?= $user['email']; ?>"><?= $user['email']; ?></a></td>
                    <td><?= $user['role']; ?></td>
                    <td><a role="button" class="btn btn-info" href="/dashboard/users/edit/<?= $user['id']; ?>">Изменить</a>
                    </td>
                    <td><a role="button" class="btn btn-danger"
                           href="/dashboard/users/delete/<?= $user['id']; ?>"
                           onclick="return confirmDelete('удалить этого пользователя');">Удалить</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Нет пользователей</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php if ($pg['count'] > 1): ?>
    <hr>
    <nav aria-label="pagination">
        <ul class="pagination pagination justify-content-center">
            <?php if ($pg['current'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="/dashboard/users/view/<?= $pg['current'] - 1; ?>">&laquo;</a>
                </li>
            <?php endif; ?>
            <?php if ($pg['start'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="/dashboard/users/view/1">1</a>
                </li>
                <?php if ($pg['start'] > 2): ?>
                    <li class="page-item">
                        <a class="page-link">...</a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
            <?php for ($i = $pg['start']; $i <= $pg['end']; $i++): ?>
                <li class="page-item<?php if ($i == $pg['current']): ?> active<?php endif; ?>">
                    <a href="/dashboard/users/view/<?= $i; ?>" class="page-link"><?= $i; ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($pg['end'] < $pg['count']): ?>
                <?php if ($pg['end'] < $pg['count'] - 1): ?>
                    <li class="page-item">
                        <a class="page-link">...</a>
                    </li>
                <?php endif; ?>
                <li class="page-item">
                    <a class="page-link" href="/dashboard/users/view/<?= $pg['count']; ?>"><?= $pg['count']; ?></a>
                </li>
            <?php endif; ?>
            <?php if ($pg['current'] < $pg['count']): ?>
                <li class="page-item">
                    <a class="page-link" href="/dashboard/users/view/<?= $pg['current'] + 1; ?>">&raquo;</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>