<?php
// data/users_api.php
declare(strict_types=1);
error_reporting(E_ALL & ~E_NOTICE);
@ini_set('display_errors', '0');

// CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
header('Content-Type: application/json; charset=utf-8');

// Helpers
function j($data, int $status = 200, array $extraHeaders = []): void {
    http_response_code($status);
    foreach ($extraHeaders as $k => $v) header("$k: $v");
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$candidates = [
    __DIR__ . '/../../data/users.txt',
    __DIR__ . '/../data/users.txt',
    __DIR__ . '/users.txt',
];

$usersFile = null;
foreach ($candidates as $p) {
    if (is_readable($p)) { $usersFile = $p; break; }
}
if (!$usersFile) j(['ok'=>false,'error'=>'users.txt not found'], 404);

// Caching headers
$mtime = filemtime($usersFile) ?: time();
$etag  = '"' . md5($usersFile . '|' . $mtime . '|' . filesize($usersFile)) . '"';
$lm    = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
header('ETag: ' . $etag);
header('Last-Modified: ' . $lm);

$ifNone = trim($_SERVER['HTTP_IF_NONE_MATCH'] ?? '');
if ($ifNone === $etag) { http_response_code(304); exit; }

// Load & parse
$lines = @file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($lines === false) j(['ok'=>false,'error'=>'read failed'], 500);

$rows = [];
foreach ($lines as $line) {
    // Robust CSV split (handles extra spaces after commas)
    $parts = array_map('trim', str_getcsv($line, ',', '"'));
    if (count($parts) < 3) continue;

    $id    = is_numeric($parts[0]) ? (int)$parts[0] : null;
    $name  = $parts[1] ?? '';
    $email = $parts[2] ?? '';

    if ($id === null || $name === '' || $email === '') continue;

    $rows[] = [
        'id'    => $id,
        'name'  => $name,
        'email' => $email,
    ];
}

// Query params: q (search), page, limit
$q     = trim($_GET['q'] ?? '');
$page  = max(1, (int)($_GET['page'] ?? 1));
$limit = (int)($_GET['limit'] ?? 50);
$limit = $limit > 0 ? min($limit, 200) : 50;

if ($q !== '') {
    $needle = mb_strtolower($q);
    $rows = array_values(array_filter($rows, function($r) use ($needle) {
        return str_contains(mb_strtolower($r['name']), $needle)
            || str_contains(mb_strtolower($r['email']), $needle);
    }));
}

// Sort by id ascending (optional)
usort($rows, fn($a,$b) => $a['id'] <=> $b['id']);

$total = count($rows);
$offset = ($page - 1) * $limit;
$data = array_slice($rows, $offset, $limit);

j([
    'ok' => true,
    'count' => count($data),
    'total' => $total,
    'page' => $page,
    'limit' => $limit,
    'data' => $data,
], 200);
