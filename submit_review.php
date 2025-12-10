<?php
session_start();
require_once 'inc/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to submit a review']);
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$user_id = $_SESSION['user_id'];
$order_number = $_POST['order_number'] ?? '';
$product_id = intval($_POST['product_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$review_text = trim($_POST['review_text'] ?? '');

// Validate inputs
if (empty($order_number)) {
    echo json_encode(['success' => false, 'message' => 'Order number is required']);
    exit();
}

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product']);
    exit();
}

if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5']);
    exit();
}

try {
    // Verify that the order belongs to the user and is delivered
    $orderCheckQuery = "SELECT o.order_id, o.order_status 
                        FROM orders o
                        WHERE o.order_number = ? AND o.user_id = ?";
    $orderCheckStmt = $conn->prepare($orderCheckQuery);
    $orderCheckStmt->bind_param("si", $order_number, $user_id);
    $orderCheckStmt->execute();
    $orderCheckResult = $orderCheckStmt->get_result();
    
    if ($orderCheckResult->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit();
    }
    
    $orderData = $orderCheckResult->fetch_assoc();
    $order_id = $orderData['order_id'];
    $order_status = $orderData['order_status'];
    $orderCheckStmt->close();
    
    if ($order_status !== 'delivered') {
        echo json_encode(['success' => false, 'message' => 'You can only review delivered orders']);
        exit();
    }
    
    // Verify that the product is in this order
    $productCheckQuery = "SELECT COUNT(*) as count FROM order_items WHERE order_id = ? AND product_id = ?";
    $productCheckStmt = $conn->prepare($productCheckQuery);
    $productCheckStmt->bind_param("ii", $order_id, $product_id);
    $productCheckStmt->execute();
    $productCheckResult = $productCheckStmt->get_result();
    $productCheck = $productCheckResult->fetch_assoc();
    $productCheckStmt->close();
    
    if ($productCheck['count'] == 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found in this order']);
        exit();
    }
    
    // Check if user already reviewed this product for this order
    $existingReviewQuery = "SELECT review_id FROM reviews WHERE user_id = ? AND product_id = ? AND order_id = ?";
    $existingReviewStmt = $conn->prepare($existingReviewQuery);
    $existingReviewStmt->bind_param("iii", $user_id, $product_id, $order_id);
    $existingReviewStmt->execute();
    $existingReviewResult = $existingReviewStmt->get_result();
    
    if ($existingReviewResult->num_rows > 0) {
        // Update existing review
        $existingReview = $existingReviewResult->fetch_assoc();
        $review_id = $existingReview['review_id'];
        $existingReviewStmt->close();
        
        $updateQuery = "UPDATE reviews SET rating = ?, review_text = ?, updated_at = CURRENT_TIMESTAMP 
                        WHERE review_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("isi", $rating, $review_text, $review_id);
        
        if ($updateStmt->execute()) {
            $updateStmt->close();
            echo json_encode([
                'success' => true, 
                'message' => 'Your review has been updated successfully!',
                'action' => 'updated'
            ]);
        } else {
            $updateStmt->close();
            echo json_encode(['success' => false, 'message' => 'Failed to update review']);
        }
    } else {
        $existingReviewStmt->close();
        
        // Insert new review
        $insertQuery = "INSERT INTO reviews (product_id, user_id, order_id, rating, review_text, is_verified_purchase, is_approved) 
                        VALUES (?, ?, ?, ?, ?, 1, 1)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("iiiis", $product_id, $user_id, $order_id, $rating, $review_text);
        
        if ($insertStmt->execute()) {
            $insertStmt->close();
            echo json_encode([
                'success' => true, 
                'message' => 'Thank you! Your review has been submitted successfully!',
                'action' => 'created'
            ]);
        } else {
            $insertStmt->close();
            echo json_encode(['success' => false, 'message' => 'Failed to submit review']);
        }
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}

$conn->close();
?>
