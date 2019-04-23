<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#333333">
    <title><?= $title; ?> <?php if ($title) { ?> - <? } ?> <?= Config::get('site.name'); ?></title>
    <link rel="stylesheet" href="/styles/bootstrap-grid.min.css">
    <link rel="stylesheet" href="/styles/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.2/jquery.fancybox.min.css">
    <link rel="stylesheet" href="/styles/main.css?v=0.0.5">
    <link rel="stylesheet" href="/styles/mobile.css?v=0.0.5">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=cyrillic-ext">
    <link rel="stylesheet" href="/styles/fontawesome.min.css">
    <link rel="stylesheet" href="/styles/solid.min.css">

    <!--    Favicon-->
    <link rel="shortcut icon" href="/images/favicon.png">
</head>
<body>
<noscript>
    <div class="container">
        <div class="row">
            <div class="col-12">Для работы с сайтом необходима поддержка JavaScript</div>
        </div>
    </div>
</noscript>

<!-- Main content -->
<div class="container main">
    <div class="row">
        <!-- Content -->

        <div class="col-12">
            <?= $content; ?>
        </div>

        <!-- End Content -->

    </div>
</div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="/scripts/main.js?v=0.0.4"></script>
<script src="/scripts/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.2/jquery.fancybox.min.js"></script>
</body>
</html>