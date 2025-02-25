<?php

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require __DIR__ . '/../vendor/autoload.php';

use Core\Env\EnvLoader;
use Core\Errors\ErrorsHandler;
use Core\Router\Router;

ErrorsHandler::init();
EnvLoader::init();
Router::init();
