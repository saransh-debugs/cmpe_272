<?php
function app_scheme(): string {
  $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
  return $https ? 'https' : 'http';
}

function app_base_path(): string {
  $dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
  return $dir === '/' ? '' : $dir;
}

function app_base_url(): string {
  $scheme = app_scheme();
  $host = $_SERVER['HTTP_HOST'];
  $path = app_base_path();
  $url = $scheme . '://' . $host . $path;
  error_log("Base URL components: scheme=$scheme, host=$host, path=$path");
  error_log("Full base URL: $url");
  return $url;
}

function url(string $path = ''): string {
  return rtrim(app_base_url(), '/') . '/' . ltrim($path, '/');
}

function asset(string $path = ''): string {
  return url($path);
}

function current_file(): string {
  return basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}

