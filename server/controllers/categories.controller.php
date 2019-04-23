<?php

class CategoriesController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->models->categories = new CategoriesModel();
    }

    public function dashboard_view()
    {
        // If not admin
        if (Session::get('role') != 1) {
            App::getRouter()->redirect('/');
            exit();
        }
        $categories = $this->models->categories->getAll();
        $res = array();
        foreach ($categories as $category) {
            $res[$category['id']] = $category;
            $parent = $this->models->categories->title($category['parent']);
            if ($parent) {
                $res[$category['id']]['parent'] = $parent;
            }
            $res[$category['id']]['col'] = $this->models->categories->productsCount($category['id']);
        }
        $this->data['categories'] = $res;
    }

    public function dashboard_add()
    {
        // If not admin
        if (Session::get('role') != 1) {
            App::getRouter()->redirect('/');
            exit();
        }

        if (isset($_POST['name']) && isset($_POST['parent'])) {
            $name = htmlspecialchars(trim($_POST['name']));
            $parent = (int)$_POST['parent'];
            $image = $_FILES['image'];
            if ($name) {
                $res = $this->models->categories->add($name, $parent, $image);
                if ($res) {
                    Session::setMessage('Категория добавлена', 'success');
                    App::getRouter()->redirect('/dashboard/categories/view');
                } else {
                    Session::setMessage('Ошибка. Возможно категория уже существует', 'danger');
                }
            } else {
                Session::setMessage('Введите все данные', 'danger');
            }
            App::getRouter()->redirect('/dashboard/categories/add');
        }

        $parents = $this->models->categories->getAll();
        $this->data['parents'] = $parents;
    }

    public function dashboard_edit()
    {
        // If not admin
        if (Session::get('role') != 1) {
            App::getRouter()->redirect('/');
            exit();
        }

        if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['parent'])) {
            $id = (int)$_POST['id'];
            $name = htmlspecialchars(trim($_POST['name']));
            $parent = (int)$_POST['parent'];
            $image = $_FILES['image'];
            if ($name) {
                $res = $this->models->categories->edit($id, $name, $parent, $image);
                if ($res) {
                    Session::setMessage('Категория изменена', 'success');
                    App::getRouter()->redirect('/dashboard/categories/view');
                } else {
                    Session::setMessage('Ошибка. Возможно категория уже существует', 'danger');
                }
            } else {
                Session::setMessage('Введите все данные', 'danger');
            }
            App::getRouter()->redirect('/dashboard/categories/edit/' . $id);
        }

        if (isset($this->params[0])) {
            $id = (int)$this->params[0];
            $category = $this->models->categories->getOne($id);
            if (!$category) App::getRouter()->redirect('/dashboard/categories/view');
            $this->data['category'] = $category;
            $parents = $this->models->categories->getAll();
            $this->data['parents'] = $parents;
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
            $res = $this->models->categories->delete($id);
            if ($res) {
                Session::setMessage('Успешно удалено', 'success');
            } else {
                Session::setMessage('Ошибка удаления. Сначала удалите или переместите товары из этой категории', 'danger');
            }
        }
        App::getRouter()->redirect('/dashboard/categories/view');
    }

    public function api_get()
    {
        $categories = $this->models->categories->getAll();

        $data = [
            'status' => 'ok',
            'categories' => $categories
        ];
        echo json_encode($data);
        exit();
    }

    public function api_one()
    {
        $id = (int) $this->params[0];
        $category = $this->models->categories->getOne($id);
        $categories = $this->models->categories->subcategories($id);

        $data = [
            'status' => 'ok',
            'category' => $category,
            'subcategories' => $categories
        ];
        echo json_encode($data);
        exit();
    }
}