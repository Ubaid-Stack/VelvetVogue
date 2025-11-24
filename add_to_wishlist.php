<?php
session_start();
require_once './inc/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'login_required' => true, 'message' => 'Please login to add items to wishlist']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);

// Check if product exists
$check_product = "SELECT product_id FROM products WHERE product_id = ? AND status = 'active'";
$stmt = $conn->prepare($check_product);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit();
}
$stmt->close();

// Check if product is already in wishlist
$check_sql = "SELECT wishlist_id FROM wishlist WHERE user_id = ? AND product_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param('ii', $user_id, $product_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // Product already in wishlist, remove it
    $wishlist_id = $check_result->fetch_assoc()['wishlist_id'];
    $check_stmt->close();
    
    $delete_sql = "DELETE FROM wishlist WHERE wishlist_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param('i', $wishlist_id);
    
    if ($delete_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Removed from wishlist', 'action' => 'removed']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove from wishlist']);
    }
    $delete_stmt->close();
} else {
    // Add to wishlist
    $check_stmt->close();
    
    $insert_sql = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param('ii', $user_id, $product_id);
    
    if ($insert_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Added to wishlist', 'action' => 'added']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add to wishlist']);
    }
    $insert_stmt->close();
}

$conn->close();
