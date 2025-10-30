<?php
// Simple users API: reads data/users.txt and returns JSON

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit;
}

header('Content-Type: application/json; charset=utf-8');

$candidateFiles = [
  // Preferred: data outside web root (one level above htdocs)
  __DIR__ . '/../../data/users.txt',
  // Fallback: data folder inside htdocs
  __DIR__ . '/../data/users.txt',
];

$file = null;
foreach ($candidateFiles as $path) {
  if (file_exists($path)) {
    $file = $path;
    break;
  }
}

if ($file === null) {
  http_response_code(404);
  echo json_encode(['error' => 'users file not found', 'looked_in' => $candidateFiles]);
  exit;
}

$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$users = [];

foreach ($lines as $line) {
  $parts = array_map('trim', explode(',', $line));
  // Support both: [ID, Name, Email] and [Name, Email, Phone]
  if (count($parts) >= 3 && is_numeric($parts[0])) {
    $id = (int)$parts[0];
    $name = $parts[1] ?? '';
    $email = $parts[2] ?? '';
    $phone = $parts[3] ?? '';
  } else {
    $id = null;
    $name = $parts[0] ?? '';
    $email = $parts[1] ?? '';
    $phone = $parts[2] ?? '';
  }

  if ($name !== '') {
    $users[] = array_filter([
      'id' => $id,
      'name' => $name,
      'email' => $email,
      'phone' => $phone,
    ], function ($v) { return $v !== null && $v !== ''; });
  }
}

echo json_encode(['users' => $users]);


