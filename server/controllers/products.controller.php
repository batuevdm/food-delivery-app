<?php

class ProductsController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->models->product = new ProductModel();
        $this->models->photo = new PhotoModel();
        $this->models->categories = new CategoriesModel();
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
        $count = $this->models->product->allCount([0, 1], 0);
        if ($count > 0) {
            // Pagination

            $productsOnPage = Config::get('products.page');

            $pagesCount = ceil($count / $productsOnPage);
            if ($pagesCount < 1) $pagesCount = 1;

            if ($page > $pagesCount || $page < 1) {
                App::getRouter()->redirect('/dashboard/products/view');
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

            $products = $this->models->product->getAll(($page - 1) * $productsOnPage, $productsOnPage, 1, 1, [0, 1], 0);
            $_products = array();
            if ($products) {
                foreach ($products as $product) {
                    $product['category'] = $this->models->categories->name($product['category']);
                    $mainPhoto = $product['main_photo'];
                    if (!$mainPhoto) {
                        $mainPhoto = Config::get('photo.default');
                    }
                    $product['main_photo'] = Config::get('storage.photo') . $mainPhoto;
                    $_products[] = $product;
                }
                $this->data['products'] = $_products;
            } else {
                $this->data['products'] = null;
            }
        } else {
            $this->data['products'] = null;
        }
    }

    public function dashboard_add()
    {
        // If not admin
        if (Session::get('role') != 1) {
            App::getRouter()->redirect('/');
            exit();
        }

        if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['col']) && isset($_POST['category'])) {
            $name = htmlspecialchars(trim($_POST['name']));
            $desc = htmlspecialchars(trim($_POST['desc']));
            $category = (int)$_POST['category'];
            $price = (int)$_POST['price'];
            $newPrice = (int)$_POST['new-price'];
            $newPrice = $newPrice == 0 ? NULL : $newPrice;
            $col = (int)$_POST['col'];
            $hide = (int)$_POST['hide'];

            $specs = array(
                'name' => $_POST['spec-name'],
                'value' => $_POST['spec-value']
            );
            $files = $_FILES;

            $res = $this->models->product->add($name, $desc, $price, $newPrice, $category, $col, $files, $specs, $hide);
            if ($res === true) {
                Session::setMessage('Товар успешно добавлен', 'success');
                App::getRouter()->redirect('/dashboard/products/view');
            } else {
                $res = $res ? $res : 'Неизвестная ошибка';
                Session::setMessage($res, 'danger');

                Session::setField('product.name', $name);
                Session::setField('product.desc', $desc);
                Session::setField('product.category', $category);
                Session::setField('product.price', $price);
                Session::setField('product.newPrice', $newPrice);
                Session::setField('product.col', $col);
                Session::setField('product.hide', $hide);
                Session::setField('product.specs', $specs);

                App::getRouter()->redirect('/dashboard/products/add');
            }
        }

        $categories = $this->models->categories->getAll();
        $this->data['categories'] = $categories;

        $specs = $this->models->product->getAllSpecs();
        $this->data['specs'] = $specs;

    }

    public function dashboard_edit()
    {
        // If not admin
        if (Session::get('role') != 1) {
            App::getRouter()->redirect('/');
            exit();
        }

        $id = (int)$this->params[0];
        $product = $this->models->product->get($id, [0, 1], 0);

        if ($product) {

            if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['col']) && isset($_POST['category'])) {
                $name = htmlspecialchars(trim($_POST['name']));
                $desc = htmlspecialchars(trim($_POST['desc']));
                $category = (int)$_POST['category'];
                $price = (int)$_POST['price'];
                $newPrice = (int)$_POST['new-price'];
                $newPrice = $newPrice == 0 ? NULL : $newPrice;
                $col = (int)$_POST['col'];
                $hide = (int)$_POST['hide'];
                $delPhotos = $_POST['del-photos'];

                $specs = array(
                    'name'  => $_POST['spec-name'],
                    'value' => $_POST['spec-value']
                );
                $files = $_FILES;

                $res = $this->models->product->update($id, $name, $desc, $price, $newPrice, $category, $col, $files, $delPhotos, $specs, $hide);
                if ($res === true) {
                    Session::setMessage('Товар успешно изменен', 'success');
                    App::getRouter()->redirect('/dashboard/products/view');
                } else {
                    $res = $res ? $res : 'Неизвестная ошибка';
                    Session::setMessage($res, 'danger');

                    Session::setField('product.name', $name);
                    Session::setField('product.desc', $desc);
                    Session::setField('product.category', $category);
                    Session::setField('product.price', $price);
                    Session::setField('product.newPrice', $newPrice);
                    Session::setField('product.col', $col);
                    Session::setField('product.hide', $hide);
                    Session::setField('product.specs', $specs);

                    App::getRouter()->redirect('/dashboard/products/edit/' . $id);
                }
            }

            $categories = $this->models->categories->getAll();
            $this->data['categories'] = $categories;

            $specs = $this->models->product->getAllSpecs();
            $this->data['allSpecs'] = $specs;

            $this->data['product'] = $product;
            $this->data['specs'] = $this->models->product->getSpecs($id);
            $this->data['photos'] = $this->models->product->getPhotos($id);

        } else {
            App::getRouter()->redirect('/dashboard/products/view');
        }
    }

    public function dashboard_delete()
    {
        // If not admin
        if (Session::get('role') != 1) {
            App::getRouter()->redirect('/');
            exit();
        }

        if (isset($this->params[0])) {
            $id = (int)$this->params[0];
            $res = $this->models->product->delete($id);
            if ($res) {
                Session::setMessage('Успешно удалено', 'success');
            } else {
                Session::setMessage('Ошибка удаления', 'danger');
            }
        }
        App::getRouter()->redirect('/dashboard/products/view');
    }

    public function api_get_new()
    {
        $products = $this->models->product->getNewProducts(9);
        $data = [
            'status'   => 'ok',
            'products' => $products
        ];

        echo json_encode($data);
        exit();
    }

    public function api_get_category()
    {
        $category = (int)$this->params[0];
        $products = $this->models->product->getByCategory($category, 0, 99999);
        $data = [
            'status'   => 'ok',
            'products' => $products
        ];

        echo json_encode($data);
        exit();
    }

    public function api_get()
    {
        $productID = (int)$this->params[0];
        $product = $this->models->product->get($productID);
        $photos = $this->models->product->getPhotos($productID);
        $specs = $this->models->product->getSpecs($productID);

//        var_dump($photos);

        $product['photos'] = (array) $photos;
        $product['specs'] = (array) $specs;
        if ($product) {
            $data = [
                'status'   => 'ok',
                'product' => $product
            ];
        } else {
            $data = [
                'status'   => 'error',
                'message' => 'Товар не существует'
            ];
        }

        echo json_encode($data);
        exit();
    }

    public function api_search()
    {
        $query = htmlspecialchars(trim($this->getGetParams()["q"]));
        if (empty($query)) $query = ":none:";

        $products = $this->models->product->search($query, 0, 999999);

        $data = [
            'status'   => 'ok',
            'query' => $query,
            'products' => $products,
        ];

        echo json_encode($data);
        exit();
    }
}