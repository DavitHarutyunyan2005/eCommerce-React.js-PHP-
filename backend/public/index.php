<?php

require __DIR__ . '/../vendor/autoload.php';

use App\GraphQL\Server;

// Allowing CORS headers
header("Access-Control-Allow-Origin: https://davit-ecommerce.store");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 
header('Content-Type: application/json'); 

try {
    Server::handle(); 
} catch (Throwable $e) { 
    http_response_code(500);
    echo json_encode([
        'error' => 'Server Error: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);
}
