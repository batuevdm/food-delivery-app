<?php

class View
{
    protected $data;
    protected $path;

    protected static function getDefaultViewPath()
    {
        $router = App::getRouter();
        if (!$router) {
            throw new Exception('Router not found');
        }

        $controller = $router->getController();
        $template = $router->getMethodPrefix() . $router->getAction() . '.php';

        return VIEWS_PATH . DS . $controller . DS . $template;
    }

    public function __construct($data = array(), $path = null)
    {
        if (!$path) {
            $path = self::getDefaultViewPath();
        }
        if (!file_exists($path)) {
            throw new Exception('Template file "' . $path . '" not found');
        }

        $this->path = $path;
        $this->data = $data;
    }

    public function render()
    {
        $data = $this->data;
        extract($data);

        ob_start();

        include $this->path;
        $content = ob_get_clean();

        return $content;
    }
}