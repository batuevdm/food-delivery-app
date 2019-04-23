<?php

class App
{
    protected static $router;
    public static $db;

    public static function getRouter()
    {
        return self::$router;
    }

    public static function run($uri)
    {
        self::$router = new Router($uri);

        self::$db = new DB(Config::get('db.host'), Config::get('db.user'), Config::get('db.pass'), Config::get('db.base'));

        $controllerClass = ucfirst(self::$router->getController()) . 'Controller';
        $controllerMethod = strtolower(self::$router->getMethodPrefix() . self::$router->getAction());
        $controllerMethod = str_replace('-', '_', $controllerMethod);

        $controller = new $controllerClass();
        if (method_exists($controller, $controllerMethod)) {
            $viewPath = $controller->$controllerMethod();
            $view = new View($controller->getData(), $viewPath);
            $content = $view->render();
        } else {
            throw new Exception('Method "' . $controllerMethod . '" not found');
        }

        $layout = self::$router->getRoute();
        $layoutPath = VIEWS_PATH . DS . $layout . '.php';

        // Template controller
        $templateController = new TemplateController();
        $method = strtolower(self::$router->getMethodPrefix()) . 'index';
        $templateController->$method();

        $layoutView = new View(array_merge(compact('content'), $templateController->getData()), $layoutPath);

        echo $layoutView->render();
    }

}