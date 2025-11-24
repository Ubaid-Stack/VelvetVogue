<?php 
session_start();
include './inc/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php?redirect=finalReview.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user information
$userQuery = "SELECT * FROM users WHERE user_id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();
$userStmt->close();

// Fetch cart items with product and variant details
$cartQuery = "SELECT c.*, p.product_name, p.price, 
              pv.size, pv.color, pv.additional_price, pv.stock_quantity,
              pi.image_url
              FROM cart c
              INNER JOIN products p ON c.product_id = p.product_id
              LEFT JOIN product_variants pv ON c.variant_id = pv.variant_id
              LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
              WHERE c.user_id = ?";
$cartStmt = $conn->prepare($cartQuery);
$cartStmt->bind_param("i", $user_id);
$cartStmt->execute();
$cartResult = $cartStmt->get_result();
$cartItems = [];
$subtotal = 0;

while ($item = $cartResult->fetch_assoc()) {
    // Fix image path
    if ($item['image_url']) {
        $item['image_url'] = str_replace('../images/', './images/', $item['image_url']);
    }
    
    $item_price = $item['price'] + ($item['additional_price'] ?? 0);
    $item['item_total'] = $item_price * $item['quantity'];
    $subtotal += $item['item_total'];
    $cartItems[] = $item;
}
$cartStmt->close();

// Get shipping method from session (if set in checkout)
$shipping_method = $_SESSION['shipping_method'] ?? 'standard';
$shipping_cost = 0;
$shipping_name = 'Standard Shipping';
$shipping_time = '5-7 business days';

switch($shipping_method) {
    case 'express':
        $shipping_cost = 15.00;
        $shipping_name = 'Express Shipping';
        $shipping_time = '2-3 business days';
        break;
    case 'overnight':
        $shipping_cost = 25.00;
        $shipping_name = 'Overnight Shipping';
        $shipping_time = '1 business day';
        break;
}

// Calculate tax and total
$tax_rate = 0.08;
$tax_amount = $subtotal * $tax_rate;
$total = $subtotal + $shipping_cost + $tax_amount;

// Fetch saved address
$addressQuery = "SELECT * FROM addresses WHERE user_id = ? AND is_default = 1 LIMIT 1";
$addressStmt = $conn->prepare($addressQuery);
$addressStmt->bind_param("i", $user_id);
$addressStmt->execute();
$savedAddress = $addressStmt->get_result()->fetch_assoc();
$addressStmt->close();

include './inc/header.php'; 
?>
    </main>
    <section class="checkout-con">
        <div class="process-flow-con">
            <div class="stage">
                <h4 class="stage-number"><i class='bx  bx-check'></i> </h4>
                <span class="stage-name">Information</span>
            </div>
            <span class="line"></span>
            <div class="stage">
                <h4 class="stage-number">2</h4>
                <span class="stage-name">Review</span>
            </div>
        </div>
        <div class="back">
            <a href="checkout.php"><span><i class='bx  bx-chevron-left'></i> Back to Checkout</span></a>
        </div>
        <div class="review-order-con">
            <div class="review-head">
                <h2>Review Your Order</h2>
                <span class="description">Please review your order details before placing your order</span>
            </div>
            
            <!-- Contact Information -->
            <div class="review-section">
                <div class="section-header">
                    <h3>Contact Information</h3>
                    <a href="checkout.php" class="edit-link"><i class='bx bx-edit-alt'></i> Edit</a>
                </div>
                <div class="section-content">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></p>
                </div>
            </div>
            
            <!-- Shipping Address -->
            <div class="review-section">
                <div class="section-header">
                    <h3>Shipping Address</h3>
                    <a href="checkout.php" class="edit-link"><i class='bx bx-edit-alt'></i> Edit</a>
                </div>
                <div class="section-content">
                    <?php if ($savedAddress): ?>
                        <p><?php echo htmlspecialchars($savedAddress['full_name']); ?></p>
                        <p><?php echo htmlspecialchars($savedAddress['address_line1']); ?></p>
                        <?php if ($savedAddress['address_line2']): ?>
                            <p><?php echo htmlspecialchars($savedAddress['address_line2']); ?></p>
                        <?php endif; ?>
                        <p><?php echo htmlspecialchars($savedAddress['city']) . ', ' . htmlspecialchars($savedAddress['state']) . ' ' . htmlspecialchars($savedAddress['zip_code']); ?></p>
                        <p><?php echo htmlspecialchars($savedAddress['country']); ?></p>
                    <?php else: ?>
                        <p style="color: #dc3545;">No shipping address provided. <a href="address.php">Add address</a></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Shipping Method -->
            <div class="review-section">
                <div class="section-header">
                    <h3>Shipping Method</h3>
                    <a href="checkout.php" class="edit-link"><i class='bx bx-edit-alt'></i> Edit</a>
                </div>
                <div class="section-content">
                    <div class="method-display">
                        <div>
                            <p class="method-name"><?php echo htmlspecialchars($shipping_name); ?></p>
                            <p class="method-time"><?php echo htmlspecialchars($shipping_time); ?></p>
                        </div>
                        <span class="method-price"><?php echo $shipping_cost == 0 ? 'Free' : '$' . number_format($shipping_cost, 2); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Payment Method -->
            <div class="review-section">
                <div class="section-header">
                    <h3>Payment Method</h3>
                    <a href="checkout.php" class="edit-link"><i class='bx bx-edit-alt'></i> Edit</a>
                </div>
                <div class="section-content">
                    <?php 
                    $payment_method = $_SESSION['payment_method'] ?? 'creditCard';
                    if ($payment_method == 'paypal'): 
                    ?>
                        <p><strong>PayPal</strong></p>
                        <p>Payment will be processed through PayPal</p>
                    <?php else: ?>
                        <p><strong>Credit/Debit Card</strong></p>
                        <p>•••• •••• •••• ••••</p>
                        <p class="text-muted" style="font-size: 0.9rem;">Card details will be securely processed</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="review-section order-items-section">
                <h3>Order Items</h3>
                <div class="order-items-list">
                    <?php if (!empty($cartItems)): ?>
                        <?php foreach ($cartItems as $item): ?>
                            <div class="order-item">
                                <img src="<?php echo htmlspecialchars($item['image_url'] ?? './images/product1.jpg'); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                <div class="item-info">
                                    <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                                    <p class="item-variant"><?php echo htmlspecialchars($item['color'] ?? 'N/A'); ?> / <?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?></p>
                                    <p class="item-quantity">Qty: <?php echo $item['quantity']; ?></p>
                                </div>
                                <span class="item-price">$<?php echo number_format($item['item_total'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Order Summary -->
                        <div class="order-summary-totals" style="margin-top: 2rem; padding-top: 1rem; border-top: 2px solid #e0e0e0;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Subtotal:</span>
                                <span>$<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Shipping:</span>
                                <span><?php echo $shipping_cost == 0 ? 'Free' : '$' . number_format($shipping_cost, 2); ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                                <span>Tax (8%):</span>
                                <span>$<?php echo number_format($tax_amount, 2); ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: bold; color: #2d2d2d;">
                                <span>Total:</span>
                                <span>$<?php echo number_format($total, 2); ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <p style="text-align: center; padding: 2rem; color: #666;">Your cart is empty</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <button class="place-order-btn">Place Order</button>
        </div>    </section>

    <script>
        document.querySelector('.place-order-btn').addEventListener('click', function() {
            const button = this;
            
            // Disable button and show loading
            button.disabled = true;
            button.textContent = 'Processing...';
            
            Swal.fire({
                title: 'Processing Order...',
                text: 'Please wait while we process your order',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send order to backend
            fetch('place_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Placed Successfully!',
                        html: `<p>Thank you for your purchase!</p>
                               <p><strong>Order Number:</strong> ${data.order_number}</p>
                               <p>Your order is being processed and you will receive a confirmation email shortly.</p>`,
                        confirmButtonText: 'View My Orders',
                        confirmButtonColor: '#3C91E6',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.href = 'order.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Order Failed',
                        text: data.message || 'Failed to place order. Please try again.',
                        confirmButtonColor: '#EF4444'
                    });
                    button.disabled = false;
                    button.textContent = 'Place Order';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while placing your order. Please try again.',
                    confirmButtonColor: '#EF4444'
                });
                button.disabled = false;
                button.textContent = 'Place Order';
            });
        });
    </script>
<?php include './inc/footer.php'; ?>
