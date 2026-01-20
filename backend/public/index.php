<?php

declare(strict_types=1);

require_once __DIR__ . '/../include/autoload.php';

use App\Database\Database;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\InternalServerException;

header('Content-Type: application/json');
$container = require __DIR__ . '/../config/container.php';

$config = require __DIR__ . '/../config/database.php';
$routes = require __DIR__ . '/../routes/api.php';

$method = strtoupper(($_SERVER['REQUEST_METHOD']) ?? 'GET');

$allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'];
if (!in_array($method, $allowedMethods)) {
    http_response_code(405);
    header('Allow: ' . implode(', ', $allowedMethods));
    echo json_encode(
        ['status' => 'error', 'message' => "HTTP method $method not allowed"],
        JSON_THROW_ON_ERROR
    );
    exit();
}

if ($method === 'OPTIONS') {
    http_response_code(204);
    header('Allow: ' . implode(', ', $allowedMethods));
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$baseDir = rtrim(dirname($scriptName), '/');

$uri = str_replace([$scriptName, $baseDir], '', $uri);
$uri = '/' . ltrim($uri, '/');
$uri = rtrim($uri, '/') ?: '/';
$uri = strtolower($uri);

try {
    $pdo = Database::connect($config);
} catch (\Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(
        ['status' => 'error', 'message' => 'Service unavailable'],
        JSON_THROW_ON_ERROR
    );
    exit();
}

if (!isset($routes[$method][$uri])) {
    http_response_code(404);
    echo json_encode(
        ['status' => 'error', 'message' => 'Route not found'],
        JSON_THROW_ON_ERROR
    );
    exit;
}

try {
    [$controllerClass, $action] = $routes[$method][$uri];
    if (!isset($container[$controllerClass])) {
        throw new InternalServerException('Controller not registered');
    }
    $controller = $container[$controllerClass]($pdo);
    if (!is_callable([$controller, $action])) {
        throw new NotFoundException('Action not found');
    }
    $response = $controller->$action();
    echo json_encode($response, JSON_THROW_ON_ERROR);

} catch (NotFoundException $e) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()], JSON_THROW_ON_ERROR);

} catch (ValidationException $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()], JSON_THROW_ON_ERROR);

} catch (UnauthorizedException $e) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()], JSON_THROW_ON_ERROR);

} catch (ForbiddenException $e) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()], JSON_THROW_ON_ERROR);

} catch (InternalServerException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()], JSON_THROW_ON_ERROR);

} catch (\JsonException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo '{"status":"error","message":"Service unavailable"}';

} catch (\Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(
        ['status' => 'error', 'message' => 'Service unavailable'],
        JSON_THROW_ON_ERROR
    );
}