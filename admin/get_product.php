<?php
session_start();
require_once '../inc/db.php';

header('Content-Type: application/json');

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit();
}

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Product ID required']);
    exit;
}

$product_id = intval($_GET['id']);

$query = "SELECT p.* FROM products p WHERE p.product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    
    // Get product image
    $imgQuery = "SELECT image_url FROM product_images WHERE product_id = ? AND is_primary = TRUE LIMIT 1";
    $imgStmt = $conn->prepare($imgQuery);
    $imgStmt->bind_param("i", $product_id);
    $imgStmt->execute();
    $imgResult = $imgStmt->get_result();
    
    if ($imgResult->num_rows > 0) {
        $image = $imgResult->fetch_assoc();
        $product['image_url'] = $image['image_url'];
    } else {
        $product['image_url'] = null;
    }
    
    // Get product variants
    // Check if color_hex column exists, if not use default query
    $columns = "variant_id, size, color, additional_price, stock_quantity, sku, is_available";
    
    // Try to get color_hex if column exists
    $checkColumn = $conn->query("SHOW COLUMNS FROM product_variants LIKE 'color_hex'");
    if ($checkColumn && $checkColumn->num_rows > 0) {
        $columns = "variant_id, size, color, color_hex, additional_price, stock_quantity, sku, is_available";
    }
    
    $variantQuery = "SELECT {$columns} FROM product_variants WHERE product_id = ? ORDER BY variant_id";
    $variantStmt = $conn->prepare($variantQuery);
    $variantStmt->bind_param("i", $product_id);
    $variantStmt->execute();
    $variantResult = $variantStmt->get_result();
    
    $product['variants'] = [];
    while ($variant = $variantResult->fetch_assoc()) {
        // Ensure color_hex has a default value
        if (!isset($variant['color_hex'])) {
            $variant['color_hex'] = '#000000';
        }
        $product['variants'][] = $variant;
    }
    $variantStmt->close();
    
    echo json_encode(['success' => true, 'product' => $product]);
} else {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
}

$stmt->close();
$conn->close();
?>
