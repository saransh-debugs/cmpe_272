<?php
function app_scheme(): string {
  $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
  return $https ? 'https' : 'http';
}

function app_base_path(): string {
  $script = $_SERVER['SCRIPT_NAME'] ?? '';
  if ($script === '') {
    return '';
  }
  $dir = rtrim(dirname($script), '/\\');
  if ($dir === '/' || $dir === '\\') {
    return '';
  }
  // If running from a subdirectory that includes "/public", trim to that base, e.g. /cmpe_272/public
  $pos = strpos($dir, '/public/');
  if ($pos !== false) {
    return substr($dir, 0, $pos + strlen('/public'));
  }
  if (substr($dir, -7) === '/public') {
    return $dir;
  }
  // Default: assume domain root
  return '';
}

function app_base_url(): string {
  return app_scheme() . '://' . $_SERVER['HTTP_HOST'];
}

function url(string $path = ''): string {
  $base = rtrim(app_base_url(), '/') . app_base_path();
  return rtrim($base, '/') . '/' . ltrim($path, '/');
}

function asset(string $path = ''): string {
  return url($path);
}

function current_file(): string {
  return basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}

