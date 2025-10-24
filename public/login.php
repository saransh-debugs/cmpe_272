<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Admin Login';

$error = '';
$redirect = isset($_GET['redirect']) ? (string)$_GET['redirect'] : (isset($_POST['redirect']) ? (string)$_POST['redirect'] : '/secure/users.php');

if (is_admin_logged_in()) {
	header('Location: ' . $redirect);
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$userid = isset($_POST['userid']) ? trim((string)$_POST['userid']) : '';
	$password = isset($_POST['password']) ? (string)$_POST['password'] : '';
	$token = isset($_POST['csrf_token']) ? (string)$_POST['csrf_token'] : '';

	if (!validate_csrf_token($token)) {
		$error = 'Invalid session token. Please try again.';
	} else if ($userid === '' || $password === '') {
		$error = 'Please enter both userid and password.';
	} else if (verify_admin_credentials($userid, $password)) {
		login_admin($userid);
		header('Location: ' . $redirect);
		exit;
	} else {
		$error = 'Invalid credentials.';
	}
}

include __DIR__ . '/includes/header.php';
?>

	<section>
		<div class="container">
			<h1 class="section-title">Admin Login</h1>
			<div class="card">
				<?php if ($error): ?>
					<div class="alert error"><?php echo htmlspecialchars($error); ?></div>
				<?php endif; ?>
				<form method="post" action="./login.php" class="form">
					<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(get_csrf_token()); ?>">
					<input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
					<div class="form-row">
						<label for="userid">User ID</label>
						<input type="text" id="userid" name="userid" autocomplete="username" required />
					</div>
					<div class="form-row">
						<label for="password">Password</label>
						<input type="password" id="password" name="password" autocomplete="current-password" required />
					</div>
					<div class="form-actions">
						<button class="button" type="submit">Log In</button>
					</div>
				</form>
			</div>
		</div>
	</section>

<?php include __DIR__ . '/includes/footer.php'; ?>


