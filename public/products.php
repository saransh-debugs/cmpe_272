<?php
require_once __DIR__ . '/includes/products_data.php';
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Services';
include __DIR__ . '/includes/header.php';
?>

	<section>
		<div class="container">
			<span class="eyebrow">Offerings</span>
			<h1 class="section-title">Services tailored to you</h1>
			<p class="section-lead">Choose one service or combine several. Every engagement includes expert planning, booking management, and on‑trip support.</p>
			<div class="space-sm"></div>
			<a class="button secondary" href="<?php echo url('products.php#recently-viewed'); ?>">Jump to recently viewed</a>

			<div class="grid cols-3">
				<?php foreach ($products as $item): ?>
					<div class="card">
						<img src="<?php echo asset($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" />
						<h3><?php echo htmlspecialchars($item['title']); ?></h3>
						<p><?php echo htmlspecialchars($item['description']); ?></p>
						<div class="space-sm"></div>
						<a class="button" href="<?php echo url('product.php?slug=' . urlencode($item['slug'])); ?>">View details</a>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="space-lg"></div>

			<?php
				$viewsFile = __DIR__ . '/../data/product_views.json';
				$views = is_file($viewsFile) ? (json_decode(@file_get_contents($viewsFile), true) ?: []) : [];
				arsort($views);
				$topSlugs = array_slice(array_keys($views), 0, 5);
			?>

			<div class="card">
				<h3>Most visited services (global)</h3>
				<?php
					if (empty($topSlugs)) {
						echo '<p class="muted">No views yet.</p>';
					} else {
						echo '<ul>';
						foreach ($topSlugs as $slug) {
							$item = get_product_by_slug($slug);
							if ($item) {
								$count = (int)($views[$slug] ?? 0);
								echo '<li><a href="' . url('product.php?slug=' . urlencode($item['slug'])) . '">' . htmlspecialchars($item['title']) . '</a> <span class="muted">(' . $count . ')</span></li>';
							}
						}
						echo '</ul>';
					}
				?>
			</div>

			<div class="card" id="recently-viewed">
				<h3>Recently viewed services</h3>
				<?php
					$recent = [];
					if (!empty($_COOKIE['recent_products'])) {
						$decoded = json_decode($_COOKIE['recent_products'], true);
						if (is_array($decoded)) { $recent = $decoded; }
					}
					if (empty($recent)) {
						echo '<p class="muted">No recently viewed services yet.</p>';
					} else {
						echo '<ul>';
						foreach ($recent as $slug) {
							$item = get_product_by_slug($slug);
							if ($item) {
								echo '<li><a href="' . url('product.php?slug=' . urlencode($item['slug'])) . '">' . htmlspecialchars($item['title']) . '</a></li>';
							}
						}
						echo '</ul>';
					}
				?>
			</div>
		</div>
	</section>

<?php include __DIR__ . '/includes/footer.php'; ?>


