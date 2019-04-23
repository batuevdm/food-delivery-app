<?php

class OrdersController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->models->order = new OrderModel();
        $this->models->user = new UserModel();
        $this->models->product = new ProductModel();
    }

    public function dashboard_view()
    {
        // If not admin
        if (Session::get('role') != 1) {
            App::getRouter()->redirect('/');
            exit();
        }

        $params = $this->getParams();
        $page = 1;
        if (isset($params[0])) {
            $page = (int)$params[0];
        }
        if ($page < 1) $page = 1;
        $count = $this->models->order->allCount();
        if ($count > 0) {
            // Pagination

            $productsOnPage = Config::get('products.page');

            $pagesCount = ceil($count / $productsOnPage);
            if ($pagesCount < 1) $pagesCount = 1;

            if ($page > $pagesCount || $page < 1) {
                App::getRouter()->redirect('/dashboard/orders/view');
            }

            $pagesShow = Config::get('pagination.pages');

            $left = $page - 1;
            if ($left < floor($pagesShow / 2)) $start = 1;
            else $start = $page - floor($pagesShow / 2);
            $end = $start + $pagesShow - 1;
            if ($end > $pagesCount) {
                $start -= ($end - $pagesCount);
                $end = $pagesCount;
                if ($start < 1) $start = 1;
            }

            $this->data['pg'] = array(
                'count' => $pagesCount,
                'current' => $page,
                'start' => $start,
                'end' => $end
            );
            // End pagination

            $orders = $this->models->order->getAll(($page - 1) * $productsOnPage, $productsOnPage, 1, 1);
            if ($orders) {
                for ($i = 0; $i < count($orders); $i++) {
                    $orders[$i]['user'] = $this->models->user->getById($orders[$i]['user']);

                    $products = $this->models->order->products($orders[$i]['id']);
                    $sum = 0;
                    foreach ($products as $product) {
                        $sum += $product['price'] * $product['col'];
                    }

                    $orders[$i]['sum'] = $sum;
                }
                $this->data['orders'] = $orders;
            } else {
                $this->data['orders'] = null;
            }
        } else {
            $this->data['orders'] = null;
        }

    }

    public function dashboard_order()
    {
        // If not admin
        if (Session::get('role') != 1) {
            App::getRouter()->redirect('/');
            exit();
        }

        $id = (int)$this->params[0];

        $order = $this->models->order->get($id);

        if ($order) {
            $this->data['order'] = $order;
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
        } else {
            App::getRouter()->redirect('/dashboard/orders/view');
            exit();
        }

    }

    public function api_new()
    {
        $name = htmlspecialchars(trim($_POST["name"]));
        $phone = htmlspecialchars(trim($_POST["phone"]));
        $cart = urldecode(trim($_POST["cart"]));

        $cart = json_decode($cart);
        $products = [];
        foreach ($cart as $cartItem) {
            $product = $this->models->product->get($cartItem->id);
            $col = (int)$cartItem->col;
            $products[] = [
                'name' => $product['name'],
                'col' => $col,
                'id' => $product['id']
            ];
        }

        $this->models->order->newFromApp($name, $phone, $products);
        echo 'ok';
        exit();
    }
}