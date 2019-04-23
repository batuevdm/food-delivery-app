<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/images/favicon.png">

    <title><?= $title; ?></title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="/styles/dashboard.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <i data-feather="menu" class="menu-open col-2 d-inline-block d-md-none"></i>
    <a class="navbar-brand col-3 col-md-2 mr-0" href="/dashboard"><?= Config::get('site.name'); ?></a>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="/account/logout">Выход</a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/categories/view">
                            <span data-feather="layers"></span>
                            Категории
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/products/view">
                            <span data-feather="gift"></span>
                            Товары
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/orders/view">
                            <span data-feather="shopping-cart"></span>
                            Заказы <span class="badge badge-success"><?= $newOrdersCol; ?></span>
                        </a>
                    </li>
                    <hr>
                    <li class="nav-item">
                        <a class="nav-link" href="/" target="_blank">
                            <span data-feather="home"></span>
                            Перейти на сайт
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <?= $content; ?>
        </main>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
<script src="/scripts/main.js?v=0.0.4"></script>

<!-- Icons -->
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace();

    $('.menu-open').click(function () {
        $('nav.sidebar').toggleClass('d-none');
    });
</script>

</body>
</html>