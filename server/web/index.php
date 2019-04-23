<?php
ob_start();
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('VIEWS_PATH', ROOT . DS . 'views');

require_once ROOT . DS . 'init.php';

try {
    session_start();
    $uri = $_SERVER['REQUEST_URI'];
    App::run($uri);
} catch (Exception $e) {
    echo '<div style="display: flex; justify-content: center; align-items: center; height: 100%;">';
    echo '<pre style="display: flex; background: red; color: white; padding: 20px;">';
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL . PHP_EOL;
//    echo 'ERROR: ' . print_r($e->getTrace(), true) . PHP_EOL . PHP_EOL;
    echo 'Trace: ' . $e->getTraceAsString();
    echo '</pre>';
    exit('<div>');
}