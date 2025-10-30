<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$pageTitle = 'Current Users';

$candidates = [
	__DIR__ . '/../../data/users.txt',
	__DIR__ . '/../data/users.txt',
];

$usersFile = null;
foreach ($candidates as $path) {
	if (file_exists($path)) {
		$usersFile = $path;
		break;
	}
}

$fetchRemoteUsers = static function (string $url): array {
	$result = [];

	$body = null;

	if (function_exists('curl_init')) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Accept: application/json, text/plain;q=0.5, */*;q=0.1',
			'User-Agent: cmpe272-site/1.0'
		]);
		$body = curl_exec($ch);
		curl_close($ch);
	}

	if (!is_string($body) || $body === '') {
		$context = stream_context_create([
			'http' => [
				'timeout' => 5,
				'header' => "Accept: application/json\r\nUser-Agent: cmpe272-site/1.0\r\n",
			],
			'ssl' => [
				'verify_peer' => true,
				'verify_peer_name' => true,
			],
		]);
		$body = @file_get_contents($url, false, $context);
	}

	if (!is_string($body) || trim($body) === '') {
		return $result;
	}

	// Try to parse JSON first
	$data = json_decode($body, true);
	if (json_last_error() === JSON_ERROR_NONE) {
		$records = [];
		if (isset($data['users']) && is_array($data['users'])) {
			$records = $data['users'];
		} elseif (is_array($data)) {
			$records = $data;
		}
		foreach ($records as $row) {
			if (is_array($row)) {
				$name = isset($row['name']) ? trim((string)$row['name']) : '';
				$email = isset($row['email']) ? trim((string)$row['email']) : '';
				$display = $name !== '' ? $name : ($email !== '' ? $email : 'Unknown');
				if ($email !== '') {
					$display .= ' — ' . $email;
				}
				$result[] = $display;
			} elseif (is_string($row)) {
				$trimmed = trim($row);
				if ($trimmed !== '') {
					$result[] = $trimmed;
				}
			}
		}
		return $result;
	}

	// Fallback: parse simple HTML lists or lines
	if (preg_match_all('/<li[^>]*>(.*?)<\\/li>/is', $body, $m)) {
		foreach ($m[1] as $item) {
			$text = trim(strip_tags($item));
			if ($text !== '') {
				$result[] = $text;
			}
		}
	} else {
		$lines = preg_split('/\r\n|\r|\n/', $body);
		foreach ($lines as $line) {
			$trimmed = trim(strip_tags($line));
			if ($trimmed !== '') {
				$result[] = $trimmed;
			}
		}
	}

	return $result;
};

$localUsers = [];
if ($usersFile !== null) {
	$lines = @file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	if ($lines !== false) {
		foreach ($lines as $line) {
			$parts = array_map('trim', explode(',', $line));
			if (count($parts) >= 3 && is_numeric($parts[0])) {
				// Format: ID, Name, Email
				$name = $parts[1] ?? '';
				$email = $parts[2] ?? '';
				$display = trim($name) !== '' ? $name : 'Unknown';
				if (trim($email) !== '') {
					$display .= ' — ' . $email;
				}
				$localUsers[] = $display;
			} else {
				// Fallback: raw line
				$trimmed = trim($line);
				if ($trimmed !== '') {
					$localUsers[] = $trimmed;
				}
			}
		}
	}
}

// Append users from external sources
$externalSources = [
	'https://dscott-cmpe272.free.nf/users.php',
	'https://mcsjsu.com/users.php',
];

$remoteUsers1 = isset($externalSources[0]) ? $fetchRemoteUsers($externalSources[0]) : [];
$remoteUsers2 = isset($externalSources[1]) ? $fetchRemoteUsers($externalSources[1]) : [];

include __DIR__ . '/../includes/header.php';
?>

	<section>
		<div class="container">
			<span class="eyebrow">Secure</span>
			<h1 class="section-title">Current Users</h1>
			<div>
					<h2 class="section-title">Saransh's User List</h2>
					<ul>
						<?php foreach ($localUsers as $u): ?>
							<li><?php echo htmlspecialchars($u); ?></li>
						<?php endforeach; ?>
					</ul>
					<?php if (empty($localUsers)): ?>
						<p class="muted">No users found.</p>
					<?php endif; ?>
					<div class="space-md"></div>
					<h2 class="section-title">Daniel's User List</h2>
					<ul>
						<?php foreach ($remoteUsers1 as $u): ?>
							<li><?php echo htmlspecialchars($u); ?></li>
						<?php endforeach; ?>
					</ul>
					<?php if (empty($remoteUsers1)): ?>
						<p class="muted">No users found.</p>
					<?php endif; ?>
					<div class="space-md"></div>
					<h2 class="section-title">Mu's User list</h2>
					<ul>
						<?php foreach ($remoteUsers2 as $u): ?>
							<li><?php echo htmlspecialchars($u); ?></li>
						<?php endforeach; ?>
					</ul>
					<?php if (empty($remoteUsers2)): ?>
						<p class="muted">No users found.</p>
					<?php endif; ?>
					<div class="space-md"></div>
				<div class="space-sm"></div>
				<a class="button secondary" href="/logout.php">Log out</a>
			</div>
		</div>
	</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>


