<?php
session_start();
require_once '../inc/db.php';

// Ensure only admins can access
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
	header('Location: adminLogin.php');
	exit();
}

$pageTitle = 'Create Admin';
$pageSubtitle = 'Promote existing customers to admin access';
$successMsg = '';
$errorMsg = '';
$currentAdminId = $_SESSION['user_id'];

// Safe logger for audit trail
function logActivity($conn, $userId, $action) {
	try {
		$stmt = $conn->prepare('INSERT INTO activity_log (user_id, action) VALUES (?, ?)');
		$stmt->bind_param('is', $userId, $action);
		$stmt->execute();
		$stmt->close();
	} catch (Exception $e) {
		// If logging fails, continue silently
	}
}

// Handle promotion request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promote_admin'])) {
	$identifier = trim($_POST['identifier'] ?? '');

	if ($identifier === '') {
		$errorMsg = 'Please enter the customer\'s email or username.';
	} else {
		$lookupSql = 'SELECT user_id, username, email, user_type FROM users WHERE email = ? OR username = ? LIMIT 1';
		$lookupStmt = $conn->prepare($lookupSql);
		$lookupStmt->bind_param('ss', $identifier, $identifier);
		$lookupStmt->execute();
		$lookupResult = $lookupStmt->get_result();

		if ($lookupResult && $lookupResult->num_rows === 1) {
			$user = $lookupResult->fetch_assoc();

			if ($user['user_type'] === 'admin') {
				$errorMsg = 'This user is already an admin.';
			} else {
				$updateStmt = $conn->prepare("UPDATE users SET user_type = 'admin' WHERE user_id = ?");
				$updateStmt->bind_param('i', $user['user_id']);

				if ($updateStmt->execute()) {
					$successMsg = 'User ' . htmlspecialchars($user['username']) . ' has been promoted to admin.';
					logActivity($conn, $currentAdminId, 'Promoted ' . $user['username'] . ' to admin');
				} else {
					$errorMsg = 'Failed to promote user. Please try again.';
				}

				$updateStmt->close();
			}
		} else {
			$errorMsg = 'No matching customer found. Check the email/username and try again.';
		}

		$lookupStmt->close();
	}
}

// Fetch all admins for quick visibility
$admins = [];
$adminStmt = $conn->prepare("SELECT user_id, username, email, created_at, last_login FROM users WHERE user_type = 'admin' ORDER BY created_at DESC");
$adminStmt->execute();
$adminResult = $adminStmt->get_result();
if ($adminResult) {
	$admins = $adminResult->fetch_all(MYSQLI_ASSOC);
}
$adminStmt->close();
?>

<?php include './inc/head.php'; ?>
<?php include './inc/sidbar.php'; ?>
<?php include './inc/topbar.php'; ?>

	<section class="analytics-section">
			<div class="section-header">
				<div class="section-title-group">
					<h2>Create Admin</h2>
					<p>Promote customers to manage your store</p>
				</div>
			</div>
			<div class="settings-card">
				<div class="settings-card-header">
					<i class='bx bx-user-plus'></i>
					<h3>Promote Customer</h3>
				</div>

				<?php if (!empty($successMsg)): ?>
					<div style="margin-bottom:16px;padding:12px 14px;border-radius:10px;background:#ECFDF3;color:#166534;border:1px solid #BBF7D0;">
						<?php echo $successMsg; ?>
					</div>
				<?php endif; ?>

				<?php if (!empty($errorMsg)): ?>
					<div style="margin-bottom:16px;padding:12px 14px;border-radius:10px;background:#FEF2F2;color:#991B1B;border:1px solid #FECACA;">
						<?php echo $errorMsg; ?>
					</div>
				<?php endif; ?>

				<form method="POST" class="settings-form">
					<div class="form-group">
						<label for="identifier">Customer Email or Username</label>
						<input type="text" id="identifier" name="identifier" placeholder="Enter customer email or username" required>
					</div>
					<button type="submit" name="promote_admin" class="promote-btn">Promote to Admin</button>
				</form>
			</div>

			<div class="settings-card">
				<div class="settings-card-header">
					<i class='bx bx-shield-quarter'></i>
					<h3>Current Admins</h3>
				</div>
				<div class="admin-table-wrapper">
					<table class="admin-table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Username</th>
								<th>Email</th>
								<th>Member Since</th>
								<th>Last Login</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($admins)): ?>
								<?php foreach ($admins as $admin): ?>
									<tr>
										<td><?php echo htmlspecialchars($admin['user_id']); ?></td>
										<td><?php echo htmlspecialchars($admin['username']); ?></td>
										<td><?php echo htmlspecialchars($admin['email']); ?></td>
										<td><?php echo htmlspecialchars(date('M d, Y', strtotime($admin['created_at']))); ?></td>
										<td><?php echo !empty($admin['last_login']) ? htmlspecialchars(date('M d, Y g:i A', strtotime($admin['last_login']))) : 'Never'; ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="5">No admins found.</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>

		<style>
			.promote-btn {
				align-self: flex-start;
				padding: 12px 18px;
				background: var(--gradient-primary);
				color: #FFFFFF;
				border: none;
				border-radius: 10px;
				font-weight: 700;
				font-size: 15px;
				cursor: pointer;
				transition: transform 0.2s ease, box-shadow 0.2s ease;
			}

			.promote-btn:hover {
				transform: translateY(-1px);
				box-shadow: 0 10px 20px rgba(60, 145, 230, 0.25);
			}
			.promote-btn:active {
				transform: translateY(0);
			}
			.admin-table-wrapper {
				overflow-x: auto;
			}
			.admin-table {
				width: 100%;
				border-collapse: collapse;
			}
			.admin-table th,
			.admin-table td {
				padding: 12px 10px;
				text-align: left;
				border-bottom: 1px solid var(--border-color);
				font-size: 14px;
				color: var(--text-primary);
			}

			.admin-table th {
				background-color: #F3F4F6;
				font-weight: 700;
			}
			.admin-table tbody tr:hover {
				background-color: #F9FAFB;
			}
		</style>

		<script>
			// Sidebar toggle for mobile
			const adminSidebar = document.getElementById('adminSidebar');
			const sidebarOverlay = document.getElementById('sidebarOverlay');
			const mobileMenuBtn = document.getElementById('mobileMenuBtn');

			if (mobileMenuBtn && adminSidebar && sidebarOverlay) {
				mobileMenuBtn.addEventListener('click', () => {
					adminSidebar.classList.toggle('active');
					sidebarOverlay.classList.toggle('active');
				});

				sidebarOverlay.addEventListener('click', () => {
					adminSidebar.classList.remove('active');
					sidebarOverlay.classList.remove('active');
				});
			}
		</script>

