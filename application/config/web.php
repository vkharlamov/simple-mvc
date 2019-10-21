<?php

$db = require __DIR__ . '/db.php';
$routes = require __DIR__ . '/routes.php';

$config = [
    'db' => $db,
    'routes' => $routes,
//    'factories' => [
//        'database' => '\application\Factory\DataBase',
//    ],
    'salt' => 'd0029793401c5b9b6b87b92a50fb58d9874ce92bdc6bae7c8f69615b09469b88', // 64
    'description_hash_algo' => 'adler32',
];

return $config;
