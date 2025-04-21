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
// header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
// Handling preflight request (OPTIONS)
// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     http_response_code(200); // Respond with OK status for OPTIONS requests
//     exit(); // End execution after preflight request
// }

// Calling the GraphQL server's handle method
try {
    Server::handle(); // Process the GraphQL request
} catch (Exception $e) {
    // Returning a JSON error message if an exception occurs
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Server Error: ' . $e->getMessage()]);
}

