<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../include/autoload.php';

use App\Database\Database;

header('Content-Type: application/json');

$config = require __DIR__ . '/../config/database.php';
$routes = require __DIR__ . '/../routes/api.php';

$method = strtoupper(($_SERVER['REQUEST_METHOD']) ?? 'GET');

$basePath = '/projects/room-booking-portal/backend/public/index.php';
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri = str_replace($basePath, '', $uri);
$uri = rtrim($uri, '/') ?: '/';
$uri = strtolower($uri);

$allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'];
if (!in_array($method, $allowedMethods)) {
    http_response_code(405);
    header('Allow: ' . implode(', ', $allowedMethods));
    echo json_encode([
        'status' => 'error',
        'message' => "HTTP method $method not allowed",
    ]);
    exit();
}

try {
    $pdo = Database::connect($config);
} catch (\Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Service Unavailable',
    ]);
    exit();
}

if (isset($routes[$method][$uri])) {
    try {
        [$controllerClass, $action] = $routes[$method][$uri];
        $controller = new $controllerClass($pdo);
        $response = $controller->$action();
        echo json_encode([
            'status' => 'success',
            'data' => $response,
        ]);

    } catch (\Throwable $e) {
        error_log($e->getMessage());
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Service unavailable',
        ]);
        exit();
    }
} else {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Route not found',
    ]);
    exit();
}