<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$pageTitle = 'Current Users';

$usersFile = __DIR__ . '/../../data/users.txt';
$users = [];
if (file_exists($usersFile)) {
	$lines = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach ($lines as $line) {
		$trimmed = trim($line);
		if ($trimmed !== '') {
			$users[] = $trimmed;
		}
	}
}

include __DIR__ . '/../includes/header.php';
?>

	<section>
		<div class="container">
			<span class="eyebrow">Secure</span>
			<h1 class="section-title">Current Users</h1>
			<div class=>
				<?php?>
					<ul>
						<?php foreach ($users as $u): ?>
							<li><?php echo htmlspecialchars($u); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php ?>
				<div class="space-sm"></div>
				<a class="button secondary" href="/logout.php">Log out</a>
			</div>
		</div>
	</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>


