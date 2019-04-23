<?php

class MainController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->models->product = new ProductModel();
        $this->models->photo = new PhotoModel();
        $this->models->stats = new StatsModel();
    }

    public function index()
    {
        header("Location: account/login");
        return;
    }

    public function dashboard_index()
    {
        // If not admin
        if (Session::get('role') != 1 ) {
            App::getRouter()->redirect('/');
            exit();
        }

        $this->data['orders'] = $this->models->stats->getOrders();
        $this->data['users'] = $this->models->stats->getUsers();
        $this->data['products'] = $this->models->stats->getProducts();
    }

}