<?php $pageTitle = 'Home'; include __DIR__ . '/includes/header.php'; ?>

	<section class="hero">
		<div class="container">
			<div class="card">
				<span class="eyebrow">Your next journey starts here</span>
				<h1>Tailor made trips to the world's most inspiring places</h1>
				<p>BayArea Travels crafts personalized itineraries, exclusive experiences, and stress‑free planning so you can focus on making memories.</p>
				<div class="cta">
					<a class="button" href="<?php echo url('products.php'); ?>">Explore Services</a>
					<a class="button secondary" href="<?php echo url('products.php#recently-viewed'); ?>">Recently Viewed</a>
					<a class="button secondary" href="<?php echo url('contacts.php'); ?>">Get in Touch</a>
				</div>
			</div>
		</div>
	</section>

	<div class="space-lg"></div>

	<section>
		<div class="container">
			<h2 class="section-title">What we do</h2>
			<p class="section-lead">From weekend getaways to multi‑country expeditions, our team designs every detail around your goals, tastes, and budget.</p>
			<div class="grid cols-3">
				<div class="card">
					<h3>Custom Itineraries</h3>
					<p>Fully bespoke trip plans including flights, stays, activities, and local guides.</p>
				</div>
				<div class="card">
					<h3>Group & Corporate</h3>
					<p>Retreats, incentives, and conferences with end‑to‑end logistics handled.</p>
				</div>
				<div class="card">
					<h3>VIP Concierge</h3>
					<p>Priority access, upgrades, and 24/7 assistance for a seamless experience.</p>
				</div>
			</div>
		</div>
	</section>

<?php include __DIR__ . '/includes/footer.php'; ?>


