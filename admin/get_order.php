<?php
session_start();
require_once '../inc/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Order ID is required']);
    exit();
}

$order_id = intval($_GET['id']);

// Fetch order with user and address details
$sql = "SELECT o.*, 
        u.username, u.email as user_email, u.phone as user_phone,
        a.full_name, a.phone as shipping_phone, a.address_line1, a.address_line2, 
        a.city, a.state, a.zip_code, a.country
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        JOIN addresses a ON o.address_id = a.address_id
        WHERE o.order_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit();
}

$order = $result->fetch_assoc();
$stmt->close();

// Fetch order items
$sql_items = "SELECT oi.*, p.product_name, pi.image_url
              FROM order_items oi
              LEFT JOIN products p ON oi.product_id = p.product_id
              LEFT JOIN product_images pi ON oi.product_id = pi.product_id AND pi.is_primary = 1
              WHERE oi.order_id = ?";

$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param('i', $order_id);
$stmt_items->execute();
$items_result = $stmt_items->get_result();

$items = [];
while ($item = $items_result->fetch_assoc()) {
    $items[] = $item;
}
$stmt_items->close();

// Format the response
$response = [
    'success' => true,
    'order' => [
        'order_id' => $order['order_id'],
        'order_number' => $order['order_number'],
        'order_status' => $order['order_status'],
        'payment_status' => $order['payment_status'],
        'payment_method' => $order['payment_method'],
        'order_date' => date('M d, Y', strtotime($order['order_date'])),
        'order_date_full' => $order['order_date'],
        'shipped_date' => $order['shipped_date'] ? date('M d, Y', strtotime($order['shipped_date'])) : null,
        'delivered_date' => $order['delivered_date'] ? date('M d, Y', strtotime($order['delivered_date'])) : null,
        'tracking_number' => $order['tracking_number'],
        'shipping_method' => $order['shipping_method'],
        'notes' => $order['notes'],
        
        // Amounts
        'subtotal' => number_format($order['subtotal'], 2),
        'shipping_cost' => number_format($order['shipping_cost'], 2),
        'tax_amount' => number_format($order['tax_amount'], 2),
        'discount_amount' => number_format($order['discount_amount'], 2),
        'total_amount' => number_format($order['total_amount'], 2),
        
        // Customer info
        'customer_name' => $order['full_name'],
        'customer_email' => $order['user_email'],
        'customer_phone' => $order['user_phone'],
        'username' => $order['username'],
        
        // Shipping address
        'shipping_full_name' => $order['full_name'],
        'shipping_phone' => $order['shipping_phone'],
        'shipping_address' => trim($order['address_line1'] . ' ' . ($order['address_line2'] ?? '')),
        'shipping_city' => $order['city'],
        'shipping_state' => $order['state'],
        'shipping_zip' => $order['zip_code'],
        'shipping_country' => $order['country'],
        'shipping_address_full' => trim($order['address_line1'] . ' ' . ($order['address_line2'] ?? '') . ', ' . $order['city'] . ', ' . $order['state'] . ' ' . $order['zip_code']),
        
        // Items
        'items' => $items,
        'item_count' => count($items)
    ]
];

echo json_encode($response);
$conn->close();
