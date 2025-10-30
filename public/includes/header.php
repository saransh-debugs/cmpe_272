<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/auth.php';
$current = current_file();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php echo isset($pageTitle) ? $pageTitle . ' — ' : ''; ?>BayArea Travels</title>
	<link rel="stylesheet" href="<?php echo asset('assets/css/styles.css'); ?>" />
</head>
<body>
	<header class="site-header">
		<div class="container">
			<a class="brand" href="<?php echo url('index.php'); ?>">
				<span>BayArea Travels</span>
			</a>
			<nav class="site-nav">
				<a href="<?php echo url('index.php'); ?>" class="<?php echo $current === 'index.php' ? 'active' : ''; ?>">Home</a>
				<a href="<?php echo url('about.php'); ?>" class="<?php echo $current === 'about.php' ? 'active' : ''; ?>">About</a>
				<a href="<?php echo url('products.php'); ?>" class="<?php echo $current === 'products.php' ? 'active' : ''; ?>">Services</a>
				<a href="<?php echo url('news.php'); ?>" class="<?php echo $current === 'news.php' ? 'active' : ''; ?>">News</a>
				<a href="<?php echo url('contacts.php'); ?>" class="<?php echo $current === 'contacts.php' ? 'active' : ''; ?>">Contacts</a>
				<?php if (is_admin_logged_in()): ?>
					<a href="<?php echo url('secure/users.php'); ?>" class="<?php echo $current === 'users.php' ? 'active' : ''; ?>">Users</a>
					<a href="<?php echo url('logout.php'); ?>">Logout</a>
				<?php else: ?>
					<a href="<?php echo url('login.php'); ?>" class="<?php echo $current === 'login.php' ? 'active' : ''; ?>">Admin Login</a>
				<?php endif; ?>
			</nav>
		</div>
	</header>

	<main>

