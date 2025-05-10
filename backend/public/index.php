<?php

require __DIR__ . '/../vendor/autoload.php';

use App\GraphQL\Server;

// Allowing CORS headers
$allowedOrigins = ['http://localhost:5173']; // Your frontend URL
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// Checking if the origin is in the allowed origins
if (in_array($origin, $allowedOrigins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
}

// Specifing allowed methods and headers
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

try {
    Server::handle(); 
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server Error: ' . $e->getMessage()]);
}

