<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../include/autoload.php';

use App\Database\Database;

$config = require __DIR__ . '/../config/database.php';

try {
    $pdo = Database::connect($config);

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => 'Connected to BookingDB successfully',
        'environment' => 'development',
    ]);

} catch (\Exception $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Service Unavailable',
    ]);
    exit();
}