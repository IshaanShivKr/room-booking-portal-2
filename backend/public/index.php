<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../include/autoload.php';

use App\Database\Database;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\InternalServerException;

header('Content-Type: application/json');

$config = require __DIR__ . '/../config/database.php';
$routes = require __DIR__ . '/../routes/api.php';

$method = strtoupper(($_SERVER['REQUEST_METHOD']) ?? 'GET');

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri = parse_url($uri, PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'];
$baseDir = dirname($scriptName);
$uri = str_replace($scriptName, '', $uri);
$uri = str_replace($baseDir, '', $uri);
$uri = '/' . ltrim($uri, '/');
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

        if ($controllerClass === \App\Controllers\RoomController::class) {
            $repository = new \App\Repositories\RoomRepository($pdo);
            $service    = new \App\Services\RoomService($repository);
            $controller = new $controllerClass($service);
        } elseif ($controllerClass === \App\Controllers\BookingController::class) {
            $repository = new \App\Repositories\BookingRepository($pdo);
            $service    = new \App\Services\BookingService($repository);
            $controller = new $controllerClass($service);
        } else {
            throw new InternalServerException("Controller mapping not found in index.php");
        }

        $response = $controller->$action();
        echo json_encode($response);

    } catch (NotFoundException $e) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);

    } catch (ValidationException $e) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);

    } catch (UnauthorizedException $e) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);

    } catch (ForbiddenException $e) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);

    } catch (InternalServerException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);

    } catch (\Throwable $e) {
        // fallback for unexpected errors
        error_log($e->getMessage());
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Service unavailable',
        ]);
    }
} else {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Route not found',
    ]);
    exit();
}