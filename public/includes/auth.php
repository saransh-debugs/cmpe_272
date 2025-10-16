<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
	$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
	session_set_cookie_params([
		'lifetime' => 0,
		'path' => '/',
		'domain' => '',
		'secure' => $secure,
		'httponly' => true,
		'samesite' => 'Lax',
	]);
	session_start();
}
if (!isset($_SESSION['initiated'])) {
	session_regenerate_id(true);
	$_SESSION['initiated'] = true;
}

function is_admin_logged_in(): bool {
	return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function require_admin(): void {
	if (!is_admin_logged_in()) {
		$requested = $_SERVER['REQUEST_URI'] ?? '/secure/users.php';
		header('Location: /login.php?redirect=' . urlencode($requested));
		exit;
	}
}

function get_csrf_token(): string {
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf_token'];
}

function validate_csrf_token(?string $token): bool {
	return is_string($token) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
function verify_admin_credentials(string $userid, string $password): bool {
	if ($userid !== 'admin') {
		return false;
	}
	$expectedPassword = 'Qazxswedcvfr123$$';
	return hash_equals($expectedPassword, $password);
}

function login_admin(string $userid): void {
	session_regenerate_id(true);
	$_SESSION['admin_logged_in'] = true;
	$_SESSION['admin_userid'] = $userid;
}

function logout_admin(): void {
	$_SESSION = [];
	if (ini_get('session.use_cookies')) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
	}
	session_destroy();
}


