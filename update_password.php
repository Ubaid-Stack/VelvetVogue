<?php
session_start();
require_once './inc/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];
$currentPassword = $_POST['currentPassword'] ?? '';
$newPassword = $_POST['newPassword'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

// Validate inputs
if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required'
    ]);
    exit();
}

if ($newPassword !== $confirmPassword) {
    echo json_encode([
        'success' => false,
        'message' => 'New passwords do not match'
    ]);
    exit();
}

if (strlen($newPassword) < 8) {
    echo json_encode([
        'success' => false,
        'message' => 'Password must be at least 8 characters long'
    ]);
    exit();
}

// Get current password from database
$query = "SELECT password FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo json_encode([
        'success' => false,
        'message' => 'User not found'
    ]);
    exit();
}

// Verify current password
if (!password_verify($currentPassword, $user['password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Current password is incorrect'
    ]);
    exit();
}

// Hash new password and update
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$updateQuery = "UPDATE users SET password = ? WHERE user_id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("si", $hashedPassword, $user_id);

if ($updateStmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Password updated successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update password. Please try again.'
    ]);
}

$updateStmt->close();
$conn->close();
?>
