<?php
session_start();
require_once './inc/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$rememberMe = isset($_POST['rememberMe']);

if (empty($username) || empty($password)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter both username and password'
    ]);
    exit();
}

// Prepare statement to prevent SQL injection
$stmt = $conn->prepare("SELECT user_id, full_name, username, password, user_type FROM users WHERE (username = ? OR email = ?)");
$stmt->bind_param('ss', $username, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid username or password'
    ]);
    $stmt->close();
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();

// Verify password
if (!password_verify($password, $user['password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid username or password'
    ]);
    exit();
}

// Set session variables
$_SESSION['user_id'] = $user['user_id'];
$_SESSION['username'] = $user['username'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['user_type'] = $user['user_type'];
$_SESSION['logged_in'] = true;

// Update last login time
$updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
$updateStmt->bind_param('i', $user['user_id']);
$updateStmt->execute();
$updateStmt->close();

// Set remember me cookie if requested (optional - for future implementation)
if ($rememberMe) {
    // You can implement remember me token here
    // For now, we'll just extend the session lifetime
    ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30); // 30 days
}

echo json_encode([
    'success' => true,
    'message' => 'Login successful',
    'user_type' => $user['user_type'],
    'redirect' => $user['user_type'] === 'admin' ? 'admin/dashboard.php' : 'shop.php'
]);

$conn->close();
?>
