<?php

class Controller
{
    protected $data;
    protected $models;
    protected $params;
    protected $getParams;

    public function getData()
    {
        return $this->data;
    }

    public function getModel()
    {
        return $this->models;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getGetParams()
    {
        return $this->getParams;
    }

    public function __construct($data = array())
    {
        $this->models = new stdClass();
        $this->data = $data;
        $this->params = App::getRouter()->getParams();
        $this->getParams = App::getRouter()->getGetParams();
    }

}