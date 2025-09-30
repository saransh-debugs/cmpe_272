<?php
$current = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php echo isset($pageTitle) ? $pageTitle . ' — ' : ''; ?>BayArea Travels</title>
	<link rel="stylesheet" href="/assets/css/styles.css" />
</head>
<body>
	<header class="site-header">
		<div class="container">
			<a class="brand" href="/index.php">
				<img src="/assets/img/logo.svg" alt="BayArea Travels logo" />
				<span>BayArea Travels</span>
			</a>
			<nav class="site-nav">
				<a href="/index.php" class="<?php echo $current === 'index.php' ? 'active' : ''; ?>">Home</a>
				<a href="/about.php" class="<?php echo $current === 'about.php' ? 'active' : ''; ?>">About</a>
				<a href="/products.php" class="<?php echo $current === 'products.php' ? 'active' : ''; ?>">Services</a>
				<a href="/news.php" class="<?php echo $current === 'news.php' ? 'active' : ''; ?>">News</a>
				<a href="/contacts.php" class="<?php echo $current === 'contacts.php' ? 'active' : ''; ?>">Contacts</a>
			</nav>
		</div>
	</header>

	<main>

