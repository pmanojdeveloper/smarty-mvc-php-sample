<?php
require_once 'controllers/ItemController.php';

$base_url = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($path, $base_url) === 0) {
    $path = substr($path, strlen($base_url));
}

$path = trim($path, '/');

$controller = new ItemController();

if ($path == '' || $path == 'index.php') {
    $controller->index();
} elseif ($path == 'create') {
    $controller->create();
} elseif (preg_match('/^edit\/(\d+)$/', $path, $matches)) {
    $controller->edit($matches[1]);
} elseif (preg_match('/^delete\/(\d+)$/', $path, $matches)) {
    $controller->delete($matches[1]);
} else {
    http_response_code(404);
    echo "Page not found";
}
