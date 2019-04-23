<?php

class AccountController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->models->user = new UserModel();
        $this->models->order = new OrderModel();
        $this->models->product = new ProductModel();
        $this->models->photo = new PhotoModel();
    }

    public function login()
    {
        if (Session::get('userid')) {
            App::getRouter()->redirect('/account/view');
            exit();
        }

        $getParams = $this->getGetParams();
        $nextQuery = isset($getParams['next']) ? '?next=' . $getParams['next'] : '';
        $this->data['next'] = $nextQuery;

        if (isset($_POST['login']) && isset($_POST['password'])) {
            $email = $_POST['login'];
            $password = $_POST['password'];

            if ($email && $password) {

                $id = $this->models->user->login($email, $password);
                $user = $this->models->user->getById($id);

                if ($user['role'] == 1) {

                    if ($id !== false) {
                        Session::set('userid', $id);
                        Session::set('role', $user['role']);
                        if (isset($getParams['next'])) App::getRouter()->redirect($getParams['next']);
                        if ($user['role'] == 1) App::getRouter()->redirect('/dashboard');
                        exit();
                    } else {
                        Session::setMessage('Неверные данные для входа', 'error');
                    }
                } else {
                    Session::setMessage('Доступ запрещен', 'error');
                }

            } else {
                Session::setMessage('Введите все данные', 'error');
            }
            Session::setField('login.email', $email);

            App::getRouter()->redirect('/account/login' . $nextQuery);
        }
    }

    public function register()
    {
        if (Session::get('userid')) {
            App::getRouter()->redirect('/account/view');
            exit();
        }

        $getParams = $this->getGetParams();
        $nextQuery = isset($getParams['next']) ? '?next=' . $getParams['next'] : '';
        $this->data['next'] = $nextQuery;

        if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['password2']) && isset($_POST['ln']) && isset($_POST['fn'])) {
            $email = $_POST['login'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            $ln = $_POST['ln'];
            $fn = $_POST['fn'];
            $mn = $_POST['mn'];
            if (empty($mn)) $mn = '';

            if ($email && $password && $password2 && $ln && $fn) {

                if ($password == $password2) {
                    $reg = $this->models->user->register($email, $password, $fn, $ln, $mn);
                    if ($reg !== false) {
                        Session::setField('login.email', $email);
                        Session::setMessage('Вы успешно зарегистрировались. Теперь можно войти', 'success');
                        App::getRouter()->redirect('/account/login' . $nextQuery);
                        exit();
                    } else {
                        Session::setMessage('Ошибка регистрации', 'error');
                    }
                } else {
                    Session::setMessage('Пароли не совпадают', 'error');
                }

            } else {
                Session::setMessage('Введите все данные', 'error');
            }

            Session::setField('reg.email', $email);
            Session::setField('reg.fn', $fn);
            Session::setField('reg.ln', $ln);
            Session::setField('reg.mn', $mn);

            App::getRouter()->redirect('/account/register' . $nextQuery);
        }
    }

    public function view()
    {
        if (!Session::get('userid')) {
            App::getRouter()->redirect('/account/login');
            exit();
        }

        $this->data['inf'] = $this->models->user->getById(Session::get('userid'));
        $this->data['ordersCol'] = $this->models->user->ordersCol(Session::get('userid'));
        $this->data['addressesCol'] = $this->models->user->addressesCol(Session::get('userid'));
    }

    public function orders()
    {
        if (!Session::get('userid')) {
            App::getRouter()->redirect('/account/login');
            exit();
        }
        $_orders = $this->models->user->getOrders(Session::get('userid'));

        $orders = array();
        foreach ($_orders as $order) {
            $orders[$order['id']] = $order;

            $products = $this->models->order->products($order['id']);
            $sum = 0;
            foreach ($products as $product) {
                $sum += $product['price'] * $product['col'];
            }

            $orders[$order['id']]['sum'] = $sum;
        }

        $this->data['orders'] = $orders;
    }

    public function order()
    {
        if (!Session::get('userid')) {
            App::getRouter()->redirect('/account/login');
            exit();
        }

        if (isset($this->params[0])) {
            switch ($this->params[0]) {
                case 'delete':
                    /*$id = (int)$this->params[1];
                    $res = $this->models->order->delete($id, Session::get('userid'));
                    if ($res) {
                        Session::setMessage('Заказ удален', 'success');
                    } else {
                        Session::setMessage('Ошибка удаления', 'error');
                    }
                    App::getRouter()->redirect('/account/orders');
                    break;*/
                case 'view':
                    $id = (int)$this->params[1];
                    $res = $this->models->order->getByUser($id, Session::get('userid'));
                    if ($res) {
                        $this->data['order'] = $res;
                        $products = $this->models->order->products($id);

                        $sum = 0;
                        $_products = array();
                        foreach ($products as $product) {
                            $sum += $product['price'] * $product['col'];
                            $_products[$product['product']] = $product;
                            $_products[$product['product']]['inf'] = $this->models->product->get($product['product'], [0, 1]);
                            $photo = $_products[$product['product']]['inf']['main_photo'] ? $_products[$product['product']]['inf']['main_photo'] : Config::get('photo.default');
                            $_products[$product['product']]['inf']['main_photo'] = Config::get('storage.photo') . $photo;
                        }

                        $this->data['orderSum'] = $sum;

                        $this->data['products'] = $_products;
                        $this->data['address'] = $this->models->user->getAddress($res['address'], Session::get('userid'))['address'];
                    } else {
                        App::getRouter()->redirect('/account/orders');
                        exit();
                    }

                    break;

                default:
                    App::getRouter()->redirect('/account/orders');
                    break;
            }
        }
    }

    public function addresses()
    {
        if (!Session::get('userid')) {
            App::getRouter()->redirect('/account/login');
            exit();
        }

        if (isset($this->params[0])) {
            switch ($this->params[0]) {
                case 'delete':
                    $id = (int)$this->params[1];
                    $res = $this->models->user->deleteAddress($id, Session::get('userid'));
                    if ($res) {
                        Session::setMessage('Адрес удален', 'success');
                    } else {
                        Session::setMessage('Ошибка удаления', 'error');
                    }
                    App::getRouter()->redirect('/account/addresses');
                    break;
                case 'add':
                    if (!isset($_POST['address'])) {
                        $isAdd = true;
                        Session::set('address.add', true);
                    } else {
                        $isAdd = false;
                        $address = trim($_POST['address']);

                        $res = $this->models->user->addAddress(Session::get('userid'), $address);
                        if ($res) {
                            Session::setMessage('Адрес добавлен', 'success');
                        } else {
                            Session::setMessage('Ошибка добавления адреса', 'error');
                        }
                        App::getRouter()->redirect('/account/addresses');
                    }
                    break;
            }
        }

        $this->data['isAdd'] = $isAdd;

        $this->data['addresses'] = $this->models->user->addresses(Session::get('userid'));
    }

    public function logout()
    {
        if (!Session::get('userid')) {
            App::getRouter()->redirect('/account/login');
            exit();
        }

        Session::set('userid', null);
        Session::set('role', null);
        App::getRouter()->redirect('/');
    }
}