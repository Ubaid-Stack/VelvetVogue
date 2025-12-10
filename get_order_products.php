<?php
session_start();
require_once 'inc/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login']);
    exit();
}

$user_id = $_SESSION['user_id'];
$order_number = $_POST['order_number'] ?? '';

if (empty($order_number)) {
    echo json_encode(['success' => false, 'message' => 'Order number is required']);
    exit();
}

try {
    // Get order ID and verify ownership
    $orderQuery = "SELECT order_id FROM orders WHERE order_number = ? AND user_id = ?";
    $orderStmt = $conn->prepare($orderQuery);
    $orderStmt->bind_param("si", $order_number, $user_id);
    $orderStmt->execute();
    $orderResult = $orderStmt->get_result();
    
    if ($orderResult->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit();
    }
    
    $orderData = $orderResult->fetch_assoc();
    $order_id = $orderData['order_id'];
    $orderStmt->close();
    
    // Fetch products from this order with existing reviews
    $productsQuery = "SELECT 
                        oi.product_id,
                        p.product_name,
                        pi.image_url,
                        r.review_id,
                        r.rating,
                        r.review_text
                      FROM order_items oi
                      JOIN products p ON oi.product_id = p.product_id
                      LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                      LEFT JOIN reviews r ON oi.product_id = r.product_id AND r.order_id = ? AND r.user_id = ?
                      WHERE oi.order_id = ?";
    
    $productsStmt = $conn->prepare($productsQuery);
    $productsStmt->bind_param("iii", $order_id, $user_id, $order_id);
    $productsStmt->execute();
    $productsResult = $productsStmt->get_result();
    
    $products = [];
    while ($product = $productsResult->fetch_assoc()) {
        // Fix image path
        $image_url = $product['image_url'] ?? './images/product1.jpg';
        if (strpos($image_url, '../images/') === 0) {
            $image_url = str_replace('../images/', './images/', $image_url);
        }
        
        $products[] = [
            'product_id' => $product['product_id'],
            'product_name' => $product['product_name'],
            'image_url' => $image_url,
            'has_review' => !is_null($product['review_id']),
            'rating' => $product['rating'],
            'review_text' => $product['review_text']
        ];
    }
    $productsStmt->close();
    
    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}

$conn->close();
?>
