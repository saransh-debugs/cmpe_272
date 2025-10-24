<?php
// Handle product detail by slug and track recently viewed products using cookies
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/products_data.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$product = get_product_by_slug($slug);

if (!$product) {
    http_response_code(404);
    $pageTitle = 'Not Found';
    include __DIR__ . '/includes/header.php';
    echo '<section><div class="container"><h1>Product not found</h1><p>The requested product does not exist.</p></div></section>';
    include __DIR__ . '/includes/footer.php';
    exit;
}
//global
$viewsFile = __DIR__ . '/../data/product_views.json';
if (!file_exists($viewsFile)) {
    // Initialize file if missing
    @file_put_contents($viewsFile, json_encode(new stdClass()), LOCK_EX);
}
$views = [];
$raw = @file_get_contents($viewsFile);
if ($raw !== false) {
    $decodedViews = json_decode($raw, true);
    if (is_array($decodedViews)) {
        $views = $decodedViews;
    }
}
$views[$slug] = isset($views[$slug]) ? ((int)$views[$slug] + 1) : 1;
@file_put_contents($viewsFile, json_encode($views, JSON_PRETTY_PRINT), LOCK_EX);

$cookieName = 'recent_products';
$recent = [];
if (!empty($_COOKIE[$cookieName])) {
    $decoded = json_decode($_COOKIE[$cookieName], true);
    if (is_array($decoded)) {
        $recent = $decoded;
    }
}

// Prepend current slug and de-duplicate
array_unshift($recent, $slug);
$recent = array_values(array_unique($recent));

// Trim to last 5
if (count($recent) > 5) {
    $recent = array_slice($recent, 0, 5);
}

// Store for 30 days; use app base path to work under subfolders
$cookiePath = app_base_path();
if ($cookiePath === '') { $cookiePath = '/'; }
setcookie($cookieName, json_encode($recent), time() + (30 * 24 * 60 * 60), $cookiePath);

$pageTitle = $product['title'];
include __DIR__ . '/includes/header.php';
?>

	<section>
		<div class="container">
			<span class="eyebrow">Service</span>
			<h1 class="section-title"><?php echo htmlspecialchars($product['title']); ?></h1>
			<p class="section-lead"><?php echo htmlspecialchars($product['description']); ?></p>

			<div class="space-md"></div>

			<div class="grid cols-2">
				<div class="card">
					<img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" style="width:100%;height:auto;" />
				</div>
				<div class="card">
					<h3>What’s included</h3>
					<ul>
						<li>Expert planning and recommendations</li>
						<li>Flexible options and transparent pricing</li>
						<li>Dedicated support before and during your trip</li>
					</ul>
					<div class="space-sm"></div>
					<a class="button" href="./contacts.php">Request this service</a>
				</div>
			</div>

			<div class="space-lg"></div>

			<a href="./products.php" class="button secondary">← Back to Services</a>
			<div class="space-sm"></div>
			<a href="./products.php#recently-viewed" class="button secondary">See recently viewed</a>
		</div>
	</section>

<?php include __DIR__ . '/includes/footer.php'; ?>


