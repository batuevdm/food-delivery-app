<h1>Статистика</h1>
<div class="stats-info justify-content-center d-flex row">
    <div class="col-sm-6 col-md-4">
        <div class="stats-item">
            <div class="item-col"><?= $orders; ?></div>
            <div class="item-name">Заказы</div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="stats-item">
            <div class="item-col"><?= $users; ?></div>
            <div class="item-name">Пользователи</div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="stats-item">
            <div class="item-col"><?= $products; ?></div>
            <div class="item-name">Товары</div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.js"></script>

<script>
    $('.item-col').counterUp({delay: 10, time: 1000});
</script>