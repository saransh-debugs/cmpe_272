<?php
$pageTitle = 'Contacts';
$dataDir = __DIR__ . '/../data';
$dataFile = $dataDir . '/contacts.txt';

if (!is_dir($dataDir)) {
	@mkdir($dataDir, 0775, true);
}

$error = '';
$success = '';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get name
    if (isset($_POST['name'])) {
        $name = trim($_POST['name']);
    } else {
        $name = '';
    }

    // Get email
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
    } else {
        $email = '';
    }

    // Get msg
    if (isset($_POST['message'])) {
        $message = trim($_POST['message']);
    } else {
        $message = '';
    }

    // Check if any field is empty
    if ($name === '' || $email === '' || $message === '') {
        $error = 'Please fill out all fields.';
    } else {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            // Prepare the record
            $record = array();
            $record['datetime'] = date('c');
            $record['name'] = $name;
            $record['email'] = $email;
            $record['message'] = str_replace(array("\r", "\n"), ' ', $message);

            // Convert the record to JSON
            $line = json_encode($record);

            // Try to save the record
            if (@file_put_contents($dataFile, $line . PHP_EOL, FILE_APPEND | LOCK_EX) !== false) {
                $success = 'Thanks! Your message has been received.';

                // Clear form fields
                $name = '';
                $email = '';
                $message = '';
            } else {
                $error = 'Could not save your message. Please try again later.';
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

	<section>
		<div class="container">
			<span class="eyebrow">Get in touch</span>
			<h1 class="section-title">Contact us</h1>
			<p class="section-lead">Please fill out the form below to contact me,  I respond in 1-2 biz days </p>

			<div class="grid cols-2">
				<div class="card">
					<form method="post" action="/contacts.php">
						<div class="form-group">
							<label for="name">Name</label>
							<input id="name" name="name" type="text" required value="<?php echo htmlspecialchars($name ?? ''); ?>" />
						</div>
						<div class="form-group">
							<label for="email">Email</label>
							<input id="email" name="email" type="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>" />
						</div>
						<div class="form-group">
							<label for="message">Message</label>
							<textarea id="message" name="message" rows="5" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
						</div>
						<button class="button" type="submit">Send message</button>
						<?php if ($error): ?>
							<div class="alert error"><?php echo htmlspecialchars($error); ?></div>
						<?php endif; ?>
						<?php if ($success): ?>
							<div class="alert success"><?php echo htmlspecialchars($success); ?></div>
						<?php endif; ?>
					</form>
				</div>
				<div class="card">
					<h3>Recent inquiries</h3>
					<?php if (file_exists($dataFile)): ?>
						<table>
							<thead>
								<tr><th>Date</th><th>Name</th><th>Email</th><th>Message</th></tr>
							</thead>
							<tbody>
								<?php
								$lines = @file($dataFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
								$lines = array_reverse($lines);
								foreach ($lines as $line) {
									$entry = json_decode($line, true);
									if (!$entry) { continue; }
									echo '<tr>';
									echo '<td>' . htmlspecialchars(date('Y-m-d H:i', strtotime($entry['datetime']))) . '</td>';
									echo '<td>' . htmlspecialchars($entry['name']) . '</td>';
									echo '<td>' . htmlspecialchars($entry['email']) . '</td>';
									echo '<td>' . htmlspecialchars($entry['message']) . '</td>';
									echo '</tr>';
								}
								?>
							</tbody>
						</table>
					<?php else: ?>
						<p class="muted">No inquiries yet.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

<?php include __DIR__ . '/includes/footer.php'; ?>


