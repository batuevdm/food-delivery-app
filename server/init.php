<?php

require_once ROOT . DS . 'config' . DS . 'config.php';

date_default_timezone_set('Europe/Samara');

function __autoload($class)
{
    $lib = ROOT . DS . 'libs' . DS . strtolower($class) . '.class.php';
    $controller = ROOT . DS . 'controllers' . DS . str_replace('controller', '', strtolower($class)) . '.controller.php';
    $model = ROOT . DS . 'models' . DS . str_replace('model', '', strtolower($class)) . '.model.php';

    if (file_exists($lib)) {
        require_once $lib;
    } elseif (file_exists($controller)) {
        require_once $controller;
    } elseif (file_exists($model)) {
        require_once $model;
    } else {
        throw new Exception('Include error: class "' . $class . '" not found');
    }
}

function vd($value, $return = true)
{
    if ($return) {
        return '<pre>' . print_r($value, true) . '</pre>';
    }
    echo '<pre>' . print_r($value, true) . '</pre>';
}

// Format price
function _p($price)
{
    return number_format($price, 0, ',', ' ');
}

// empty tpl
function _null()
{
    return VIEWS_PATH . DS . 'empty.php';
}

function num2word($num, $words)
{
    $num = $num % 100;
    if ($num > 19) {
        $num = $num % 10;
    }
    switch ($num) {
        case 1:
            {
                return ($words[0]);
            }
        case 2:
        case 3:
        case 4:
            {
                return ($words[1]);
            }
        default:
            {
                return ($words[2]);
            }
    }
}

function hashPassword($password)
{
    $salt = Config::get('password.salt');
    return sha1(md5($password . $salt) . $salt);
}

function dateFormat($unix)
{
    return date('d.m.Y в H:i:s', $unix);
}

function isJson($string)
{
    $res = json_decode($string);
    return is_object($res);
}

define('ERROR_FILE_EXTENSION', 0x1);
define('ERROR_FILE_UPLOAD', 0x2);
define('ERROR_FILE_EMPTY', 0x3);

function uploadPhoto($_file)
{
    var_dump($_file);

    $dir = ROOT . '/web/' . Config::get('storage.photo');

    $extensions = array('png', 'jpg', 'jpeg', 'gif', 'bmp');

    if (is_array($_file['name'])) {
        $files = array();
        $count = count($_file['name']);
        foreach ($_file as $key => $value) {
            for ($i = 0; $i < $count; $i++) {
                $files[$i][$key] = $value[$i];
            }
        }
    } else {
        $files = array($_file);
    }

    $names = array();

    foreach ($files as $file) {
        $name = basename($file['name']);
        $ext = explode('.', $name);
        $ext = $ext[count($ext) - 1];

        if ($file['error'] == UPLOAD_ERR_NO_FILE) {
            $names[] = ERROR_FILE_EMPTY;
            continue;
        }

        if ($file['error'] != UPLOAD_ERR_OK) {
            $names[] = ERROR_FILE_UPLOAD;
            continue;
        }

        if (!in_array($ext, $extensions)) {
            $names[] = ERROR_FILE_EXTENSION;
            continue;
        }

        $name = 'photo_' . md5(uniqid(rand(),1)) . '.' . $ext;

        if (move_uploaded_file($file['tmp_name'], $dir . $name)) {
            $names[] = $name;
            continue;
        }

        $names[] = ERROR_FILE_UPLOAD;
        continue;

    }

    return $names;
}