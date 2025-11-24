<?php
session_start();
require_once './inc/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([
        'success' => false,
        'login_required' => true,
        'message' => 'Please login to add items to cart'
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

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;

// Verify product exists and is active
$product_check_sql = "SELECT product_id, product_name, stock_quantity, status FROM products WHERE product_id = ?";
$product_stmt = $conn->prepare($product_check_sql);
$product_stmt->bind_param('i', $product_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

if ($product_result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Product not found'
    ]);
    $product_stmt->close();
    exit();
}

$product = $product_result->fetch_assoc();
$product_stmt->close();

// Check if product is active
if ($product['status'] !== 'active') {
    echo json_encode([
        'success' => false,
        'message' => 'This product is no longer available'
    ]);
    exit();
}

// Check if enough stock is available
if ($product['stock_quantity'] < $quantity) {
    echo json_encode([
        'success' => false,
        'message' => 'Not enough stock available. Only ' . $product['stock_quantity'] . ' items left.'
    ]);
    exit();
}

// Check if item already exists in cart
$cart_check_sql = "SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND variant_id IS NULL";
$cart_check_stmt = $conn->prepare($cart_check_sql);
$cart_check_stmt->bind_param('ii', $user_id, $product_id);
$cart_check_stmt->execute();
$cart_result = $cart_check_stmt->get_result();

if ($cart_result->num_rows > 0) {
    // Item already in cart, update quantity
    $cart_item = $cart_result->fetch_assoc();
    $new_quantity = $cart_item['quantity'] + $quantity;
    
    // Check if new quantity exceeds stock
    if ($new_quantity > $product['stock_quantity']) {
        echo json_encode([
            'success' => false,
            'message' => 'Cannot add more items. Maximum stock available: ' . $product['stock_quantity']
        ]);
        $cart_check_stmt->close();
        exit();
    }
    
    $update_sql = "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE cart_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ii', $new_quantity, $cart_item['cart_id']);
    
    if ($update_stmt->execute()) {
        echo json_encode([
            'success' => true,
            'action' => 'updated',
            'message' => 'Cart updated! Quantity: ' . $new_quantity
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update cart'
        ]);
    }
    $update_stmt->close();
} else {
    // Add new item to cart
    $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param('iii', $user_id, $product_id, $quantity);
    
    if ($insert_stmt->execute()) {
        echo json_encode([
            'success' => true,
            'action' => 'added',
            'message' => 'Added to cart!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add to cart'
        ]);
    }
    $insert_stmt->close();
}

$cart_check_stmt->close();
$conn->close();
?>
