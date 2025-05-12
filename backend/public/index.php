<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use App\GraphQL\Server;

// Allowing CORS headers
$allowedOrigins = ['https://www.davit-ecommerce.store'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowedOrigins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
}

// Always send headers
header("Access-Control-Allow-Origin: https://www.davit-ecommerce.store");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");



header('Content-Type: application/json'); // Always respond JSON (Apollo expects this)

try {
    Server::handle(); 
} catch (Throwable $e) {  // Catch all errors (Exception + fatal errors)
    http_response_code(500);
    echo json_encode([
        'error' => 'Server Error: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);
}
