<?php

class Router
{
    protected $uri;
    protected $controller;
    protected $action;
    protected $params;
    protected $getParams;
    protected $route;
    protected $methodPrefix;
    protected $language;

    public function getUri()
    {
        return $this->uri;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getGetParams()
    {
        return $this->getParams;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getMethodPrefix()
    {
        return $this->methodPrefix;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function __construct($uri)
    {
        $this->uri = urldecode(trim($uri, '/'));

        $routes = Config::get('routes');
        $this->route = Config::get('default.route');
        $this->methodPrefix = $routes[$this->route] ? $routes[$this->route] : '';
        $this->language = Config::get('default.language');
        $this->controller = Config::get('default.controller');
        $this->action = Config::get('default.action');

        $uriParts = explode('?', $this->uri);

        if(!isset($uriParts[1]) or empty($uriParts[1])) {
            $this->getParams = null;
        } else {
            $get = $uriParts[1];

            $getParts = explode('&', $get);
            $getParams = array();
            foreach ($getParts as $part) {
                $parts = explode('=', $part);
                $getParams[$parts[0]] = $parts[1];
            }

            $this->getParams = $getParams;
        }

        $path = $uriParts[0];

        $pathParts = explode('/', $path);

        if (count($pathParts)) {
            if (in_array(strtolower(current($pathParts)), array_keys($routes))) {
                $this->route = strtolower(current($pathParts));
                $this->methodPrefix = isset($routes[$this->route]) ? $routes[$this->route] : '';
                array_shift($pathParts);
            } elseif (in_array(strtolower(current($pathParts)), Config::get('languages'))) {
                $this->language = strtolower(current($pathParts));
                array_shift($pathParts);
            }

            if (current($pathParts)) {
                $this->controller = strtolower(current($pathParts));
                array_shift($pathParts);
            }

            if (current($pathParts)) {
                $this->action = strtolower(current($pathParts));
                array_shift($pathParts);
            }

            $this->params = $pathParts;
            if ( $this->methodPrefix == 'api_' ) header('Content-type: application/json');
        }
    }

    public function redirect($location)
    {
        header('Location: ' . $location);
        exit();
    }

}