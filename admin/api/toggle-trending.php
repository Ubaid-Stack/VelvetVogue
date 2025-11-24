<?php
session_start();
require_once '../../inc/db.php';

header('Content-Type: application/json');

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit();
}

// Check if product_id is provided
if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Product ID is required'
    ]);
    exit();
}

$product_id = intval($_POST['product_id']);

// Get current featured status
$check_sql = "SELECT is_featured FROM products WHERE product_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param('i', $product_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Product not found'
    ]);
    $check_stmt->close();
    exit();
}

$product = $result->fetch_assoc();
$check_stmt->close();

// Toggle the is_featured status
$new_status = $product['is_featured'] ? 0 : 1;

$update_sql = "UPDATE products SET is_featured = ? WHERE product_id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param('ii', $new_status, $product_id);

if ($update_stmt->execute()) {
    echo json_encode([
        'success' => true,
        'is_trending' => $new_status,
        'message' => $new_status ? 'Product marked as trending' : 'Product removed from trending'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update trending status'
    ]);
}

$update_stmt->close();
$conn->close();
?>
