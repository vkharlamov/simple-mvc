<?php

//require_once __DIR__ . '/../application/lib/Dev.php';
require_once __DIR__ . '/../vendor/autoload.php';
define('ABSOLUTE_ROOT_PATH', dirname(dirname(__FILE__)) . '/');

$config = require_once __DIR__ . '/../application/config/web.php';

spl_autoload_register(function ($class) {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class . '.php');
    require_once '../' . $path;
}
);

session_start();

$app = (new application\core\Application($config))->init();
$app::$router->run();
