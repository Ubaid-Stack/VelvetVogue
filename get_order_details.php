<?php
session_start();
include './inc/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Please log in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$order_number = $_POST['order_number'] ?? '';

if (empty($order_number)) {
    echo json_encode(['success' => false, 'message' => 'Order number required']);
    exit();
}

// Fetch order details
$orderQuery = "SELECT o.*, 
               a.full_name, a.phone, a.address_line1, a.address_line2, 
               a.city, a.state, a.zip_code, a.country
               FROM orders o
               LEFT JOIN addresses a ON o.address_id = a.address_id
               WHERE o.order_number = ? AND o.user_id = ?";

$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("si", $order_number, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit();
}

// Fetch order items
$itemsQuery = "SELECT oi.*, pi.image_url
               FROM order_items oi
               LEFT JOIN product_images pi ON oi.product_id = pi.product_id AND pi.is_primary = 1
               WHERE oi.order_id = ?";
$itemsStmt = $conn->prepare($itemsQuery);
$itemsStmt->bind_param("i", $order['order_id']);
$itemsStmt->execute();
$itemsResult = $itemsStmt->get_result();
$items = [];
while ($item = $itemsResult->fetch_assoc()) {
    if ($item['image_url']) {
        $item['image_url'] = str_replace('../images/', './images/', $item['image_url']);
    }
    $items[] = $item;
}
$itemsStmt->close();

// Format payment method
$payment_methods = [
    'credit_card' => 'Credit Card',
    'debit_card' => 'Debit Card',
    'paypal' => 'PayPal',
    'cash_on_delivery' => 'Cash on Delivery'
];

// Format order status
$order_statuses = [
    'pending' => 'Pending',
    'processing' => 'Processing',
    'shipped' => 'Shipped',
    'delivered' => 'Delivered',
    'cancelled' => 'Cancelled',
    'refunded' => 'Refunded'
];

// Build shipping address
$shipping_address = $order['full_name'] . '<br>';
if ($order['phone']) {
    $shipping_address .= $order['phone'] . '<br>';
}
$shipping_address .= $order['address_line1'] . '<br>';
if ($order['address_line2']) {
    $shipping_address .= $order['address_line2'] . '<br>';
}
$shipping_address .= $order['city'] . ', ' . $order['state'] . ' ' . $order['zip_code'] . '<br>';
$shipping_address .= $order['country'];

echo json_encode([
    'success' => true,
    'order' => [
        'order_number' => $order['order_number'],
        'order_date' => date('F j, Y', strtotime($order['order_date'])),
        'order_status' => $order_statuses[$order['order_status']] ?? $order['order_status'],
        'payment_status' => ucfirst($order['payment_status']),
        'payment_method' => $payment_methods[$order['payment_method']] ?? $order['payment_method'],
        'subtotal' => number_format($order['subtotal'], 2),
        'shipping_cost' => number_format($order['shipping_cost'], 2),
        'tax_amount' => number_format($order['tax_amount'], 2),
        'total_amount' => number_format($order['total_amount'], 2),
        'shipping_method' => $order['shipping_method'],
        'shipping_address' => $shipping_address,
        'tracking_number' => $order['tracking_number'],
        'shipped_date' => $order['shipped_date'] ? date('F j, Y', strtotime($order['shipped_date'])) : null,
        'delivered_date' => $order['delivered_date'] ? date('F j, Y', strtotime($order['delivered_date'])) : null,
        'items' => $items
    ]
]);

$conn->close();
?>
