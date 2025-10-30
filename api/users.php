<?php
// Wrapper to serve API when project root is document root
// Redirect/forward to public/api/users.php

// If running with docroot at project root, include the script directly
$publicApi = __DIR__ . '/public/api/users.php';
if (file_exists($publicApi)) {
  require $publicApi;
  exit;
}

// Fallback: try relative path
header('Content-Type: application/json');
http_response_code(500);
echo json_encode(['error' => 'API script not found']);

