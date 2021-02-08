<?php session_start();

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/' :
        require __DIR__ . '/views/index.php';
        break;
    case '' :
        require __DIR__ . '/views/index.php';
        break;
    case '/index.php' :
        require __DIR__ . '/views/index.php';
        break;
    case '/login' :
        require __DIR__ . '/login.php';
        break;
    case '/register' :
        require __DIR__ . '/create_user.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.php';
        break;
}