<?php
session_start();
include './inc/db.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Please log in to place an order']);
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Start transaction
    $conn->begin_transaction();
    
    // Fetch cart items
    $cartQuery = "SELECT c.*, p.product_name, p.price, p.sku,
                  pv.size, pv.color, pv.additional_price, pv.stock_quantity
                  FROM cart c
                  INNER JOIN products p ON c.product_id = p.product_id
                  LEFT JOIN product_variants pv ON c.variant_id = pv.variant_id
                  WHERE c.user_id = ?";
    $cartStmt = $conn->prepare($cartQuery);
    $cartStmt->bind_param("i", $user_id);
    $cartStmt->execute();
    $cartResult = $cartStmt->get_result();
    $cartItems = $cartResult->fetch_all(MYSQLI_ASSOC);
    $cartStmt->close();
    
    // Check if cart is empty
    if (empty($cartItems)) {
        throw new Exception('Your cart is empty');
    }
    
    // Get default address
    $addressQuery = "SELECT address_id FROM addresses WHERE user_id = ? AND is_default = 1 LIMIT 1";
    $addressStmt = $conn->prepare($addressQuery);
    $addressStmt->bind_param("i", $user_id);
    $addressStmt->execute();
    $addressResult = $addressStmt->get_result();
    $address = $addressResult->fetch_assoc();
    $addressStmt->close();
    
    if (!$address) {
        throw new Exception('Please add a shipping address');
    }
    
    $address_id = $address['address_id'];
    
    // Calculate totals
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $item_price = $item['price'] + ($item['additional_price'] ?? 0);
        $subtotal += $item_price * $item['quantity'];
    }
    
    // Get shipping method from session
    $shipping_method = $_SESSION['shipping_method'] ?? 'standard';
    $shipping_cost = 0;
    $shipping_name = 'Standard Shipping';
    
    switch($shipping_method) {
        case 'express':
            $shipping_cost = 15.00;
            $shipping_name = 'Express Shipping';
            break;
        case 'overnight':
            $shipping_cost = 25.00;
            $shipping_name = 'Overnight Shipping';
            break;
        default:
            $shipping_name = 'Standard Shipping';
    }
    
    // Calculate tax and total
    $tax_rate = 0.08;
    $tax_amount = $subtotal * $tax_rate;
    $total_amount = $subtotal + $shipping_cost + $tax_amount;
    
    // Get payment method from session
    $payment_method = $_SESSION['payment_method'] ?? 'credit_card';
    
    // Map payment method
    $payment_method_db = 'credit_card';
    if ($payment_method == 'paypal') {
        $payment_method_db = 'paypal';
    }
    
    // Generate order number
    $order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    
    // Insert order
    $orderQuery = "INSERT INTO orders (order_number, user_id, address_id, order_status, payment_status, 
                   payment_method, subtotal, shipping_cost, tax_amount, total_amount, shipping_method) 
                   VALUES (?, ?, ?, 'pending', 'pending', ?, ?, ?, ?, ?, ?)";
    $orderStmt = $conn->prepare($orderQuery);
    $orderStmt->bind_param("siisdddds", $order_number, $user_id, $address_id, $payment_method_db, 
                          $subtotal, $shipping_cost, $tax_amount, $total_amount, $shipping_name);
    
    if (!$orderStmt->execute()) {
        throw new Exception('Failed to create order');
    }
    
    $order_id = $conn->insert_id;
    $orderStmt->close();
    
    // Insert order items and update stock
    $itemQuery = "INSERT INTO order_items (order_id, product_id, variant_id, product_name, product_sku,
                  size, color, quantity, unit_price, subtotal) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $itemStmt = $conn->prepare($itemQuery);
    
    $updateStockQuery = "UPDATE product_variants SET stock_quantity = stock_quantity - ? 
                        WHERE variant_id = ? AND stock_quantity >= ?";
    $updateStockStmt = $conn->prepare($updateStockQuery);
    
    foreach ($cartItems as $item) {
        $unit_price = $item['price'] + ($item['additional_price'] ?? 0);
        $item_subtotal = $unit_price * $item['quantity'];
        
        $variant_id = $item['variant_id'] ?? null;
        $size = $item['size'] ?? null;
        $color = $item['color'] ?? null;
        
        $itemStmt->bind_param("iiisssiidd", $order_id, $item['product_id'], $variant_id,
                             $item['product_name'], $item['sku'], $size, $color,
                             $item['quantity'], $unit_price, $item_subtotal);
        
        if (!$itemStmt->execute()) {
            throw new Exception('Failed to add order items');
        }
        
        // Update stock if variant exists
        if ($variant_id) {
            $updateStockStmt->bind_param("iii", $item['quantity'], $variant_id, $item['quantity']);
            if (!$updateStockStmt->execute()) {
                throw new Exception('Insufficient stock for ' . $item['product_name']);
            }
            
            if ($updateStockStmt->affected_rows == 0) {
                throw new Exception('Insufficient stock for ' . $item['product_name']);
            }
        }
    }
    
    $itemStmt->close();
    $updateStockStmt->close();
    
    // Clear cart
    $clearCartQuery = "DELETE FROM cart WHERE user_id = ?";
    $clearCartStmt = $conn->prepare($clearCartQuery);
    $clearCartStmt->bind_param("i", $user_id);
    $clearCartStmt->execute();
    $clearCartStmt->close();
    
    // Clear session variables
    unset($_SESSION['shipping_method']);
    unset($_SESSION['payment_method']);
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully!',
        'order_id' => $order_id,
        'order_number' => $order_number
    ]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    // Log the error
    error_log("Place Order Error: " . $e->getMessage() . " | File: " . $e->getFile() . " | Line: " . $e->getLine());
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}

$conn->close();
?>
