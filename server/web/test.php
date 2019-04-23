<?php
/* Входные параметры */
$count_pages = 13;
$active = $_GET['page'];
$count_show_pages = 9;
$url = "?page=1";
$url_page = "?page=";
if ($count_pages > 1) { // Всё это только если количество страниц больше 1
    /* Дальше идёт вычисление первой выводимой страницы и последней (чтобы текущая страница была где-то посредине, если это возможно, и чтобы общая сумма выводимых страниц была равна count_show_pages, либо меньше, если количество страниц недостаточно) */
    $left = $active - 1;
//    $right = $count_pages - $active;
    if ($left < floor($count_show_pages / 2)) $start = 1;
    else $start = $active - floor($count_show_pages / 2);
    $end = $start + $count_show_pages - 1;
    if ($end > $count_pages) {
        $start -= ($end - $count_pages);
        $end = $count_pages;
        if ($start < 1) $start = 1;
    }
    ?>
    <!-- Дальше идёт вывод Pagination -->
    <div id="pagination">
        <span>Страницы: </span>
        <?php if ($active != 1) { ?>
            <a href="<?= $url ?>" title="Первая страница">&lt;&lt;&lt;</a>
            <a href="<?php if ($active == 2) { ?><?= $url ?><?php } else { ?><?= $url_page . ($active - 1) ?><?php } ?>"
               title="Предыдущая страница">&lt;</a>
        <?php } ?>
        <?php for ($i = $start; $i <= $end; $i++) { ?>
            <?php if ($i == $active) { ?><span><?= $i ?></span><?php } else { ?><a
                href="<?php if ($i == 1) { ?><?= $url ?><?php } else { ?><?= $url_page . $i ?><?php } ?>"><?= $i ?></a><?php } ?>
        <?php } ?>
        <?php if ($active != $count_pages) { ?>
            <a href="<?= $url_page . ($active + 1) ?>" title="Следующая страница">&gt;</a>
            <a href="<?= $url_page . $count_pages ?>" title="Последняя страница">&gt;&gt;&gt;</a>
        <?php } ?>
    </div>
<?php } ?>