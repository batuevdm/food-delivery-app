<?php

class TemplateController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->models->categories = new CategoriesModel();
        $this->models->product = new ProductModel();
        $this->models->stats = new StatsModel();
    }

    public function index()
    {
        $data = array();

        $categories = $this->models->categories->get(true);
        $data['categories'] = $categories;

        $titles = array(
            'products' => array(
                'category' => $this->models->categories->title(App::getRouter()->getParams()[0]),
                'product' => $this->models->product->title(App::getRouter()->getParams()[0]),
                'search' => 'Поиск - ' . htmlspecialchars($_GET['q']),
            ),
            'account' => array(
                'login' => 'Вход',
                'register' => 'Регистрация',
                'view' => 'Аккаунт',
                'orders' => 'Заказы',
                'addresses' => 'Адреса',
            ),
            'cart' => array(
                'index' => 'Корзина',
                'order' => 'Оформление заказа',
            )
        );
        $title = $titles[App::getRouter()->getController()][App::getRouter()->getAction()];
        $data['title'] = $title;

        $this->data = $data;
    }

    public function ajax_index()
    {

    }

    public function dashboard_index()
    {
        $data = array();

        $titles = array(
            'main' => array(
                'index' => 'Панель управления',
            ),
            'categories' => array(
                'view' => 'Просмотр категорий',
                'add' => 'Добавление категории',
                'edit' => 'Редактирование категории',
            ),
            'products' => array(
                'view' => 'Просмотр товаров',
                'add' => 'Добавление товара',
                'edit' => 'Редактирование товара',
            ),
            'users' => array(
                'view' => 'Просмотр пользователей',
                'add' => 'Добавление пользователя',
                'edit' => 'Редактирование пользователя',
            ),
            'orders' => array(
                'view' => 'Просмотр заказов',
                'order' => 'Заказ',
            ),
        );

        $title = $titles[App::getRouter()->getController()][App::getRouter()->getAction()];
        $data['title'] = $title;

        $data['newOrdersCol'] = $this->models->stats->getNewOrders();

        $this->data = $data;
    }
}