<?php 
session_start();
include './inc/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php?redirect=checkout.php');
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

// Calculate shipping and tax
$shipping_cost = 0; // Free standard shipping
$tax_rate = 0.08; // 8% tax
$tax_amount = $subtotal * $tax_rate;
$total = $subtotal + $shipping_cost + $tax_amount;

// Fetch user's saved addresses
$addressQuery = "SELECT * FROM addresses WHERE user_id = ? AND is_default = 1 LIMIT 1";
$addressStmt = $conn->prepare($addressQuery);
$addressStmt->bind_param("i", $user_id);
$addressStmt->execute();
$savedAddress = $addressStmt->get_result()->fetch_assoc();
$addressStmt->close();

include './inc/header.php'; 
?>
   
    <section class="checkout-con">
        <div class="process-flow-con">
            <div class="stage">
                <h4 class="stage-number">1</h4>
                <span class="stage-name">Information</span>
            </div>
            <span class="line"></span>
            <div class="stage">
                <h4 class="stage-number">2</h4>
                <span class="stage-name">Review</span>
            </div>
        </div>
        <div class="back">
            <a href="cart.php"><span><i class='bx  bx-chevron-left'></i> Back to Cart</span></a>
        </div>
        <div class="checkout-card">
            <h2>Secure Checkout</h2>
            <div class="contact-infor">
                <h3>Contact Information</h3>
                <form class="contact-form" action="#" method="post">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Enter your email address" required>

                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="Enter your phone number" required>
                </form>
            </div>
            <div class="shipping-address">
                <h3>Shipping Address</h3>
                <form class="shipping-form" method="post" action="">
                    <?php
                    $nameParts = explode(' ', $user['full_name'] ?? '', 2);
                    $firstName = $nameParts[0] ?? '';
                    $lastName = $nameParts[1] ?? '';
                    ?>
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" placeholder="Enter your first name" required>

                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" placeholder="Enter your last name" required>

                    <label for="address">Street Address:</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($savedAddress['street_address'] ?? ''); ?>" placeholder="Enter your street address" required>

                    <label for="apartment">Apartment, suite, etc. (optional):</label>
                    <input type="text" id="apartment" name="apartment" value="<?php echo htmlspecialchars($savedAddress['apartment'] ?? ''); ?>" placeholder="Enter your apartment number">

                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($savedAddress['city'] ?? ''); ?>" placeholder="Enter your city" required>

                    <label for="state">State/Province:</label>
                    <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($savedAddress['state'] ?? ''); ?>" placeholder="Enter your state or province" required>

                    <label for="zip">ZIP/Postal Code:</label>
                    <input type="text" id="zip" name="zip" value="<?php echo htmlspecialchars($savedAddress['zip_code'] ?? ''); ?>" placeholder="Enter your ZIP or postal code" required>
                </form>
            </div>
            <div class="shippinng-method">
                <h2 class="title"><i class='bx  bx-package'></i> Shipping Method</h2>
                <div class="ship-method">
                    <div class="method-option">
                        <input type="radio" id="standard" name="shippingMethod" value="standard" checked>
                        <label for="standard">
                            <span class="method-name">Standard Shipping</span>
                            <span class="method-details">Delivery in 5-7 business days - Free</span>
                        </label>
                    </div>
                    <div class="method-option">
                        <input type="radio" id="express" name="shippingMethod" value="express">
                        <label for="express">
                            <span class="method-name">Express Shipping</span>
                            <span class="method-details">Delivery in 2-3 business days - $15.00</span>
                        </label>
                    </div>
                    <div class="method-option">
                        <input type="radio" id="overnight" name="shippingMethod" value="overnight">
                        <label for="overnight">
                            <span class="method-name">Overnight Shipping</span>
                            <span class="method-details">Delivery in 1 business day - $25.00</span>
                        </label>
                    </div>

                </div>
            </div>
            <div class="payment-method">
                <h2 class="title"><i class='bx  bx-credit-card-front'></i> Payment Method</h2>
                <div class="payment-option">
                    <input type="radio" id="creditCard" name="paymentMethod" value="creditCard" checked>
                    <label for="creditCard">
                        <span class="method-name">Credit/Debit Card</span>
                        <span class="method-details">We accept Visa, MasterCard, American Express, and Discover.</span>
                    </label>
                </div>
                <div class="payment-option">
                    <input type="radio" id="paypal" name="paymentMethod" value="paypal">
                    <label for="paypal">
                        <span class="method-name">PayPal</span>
                        <span class="method-details">Secure payment through your PayPal account.</span>
                    </label>
                </div>
                <label for="cardNumber">Card Number:</label>
                <input type="text" id="cardNumber" name="cardNumber" placeholder="Enter your card number" required>

                <label for="nameOnCard">Name on Card:</label>
                <input type="text" id="nameOnCard" name="nameOnCard" placeholder="Enter the name on your card" required>

                <label for="expiryDate">Expiry Date:</label>
                <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY" required>

                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" placeholder="Enter CVV" required>

                <a href="finalReview.php"><button type="submit" class="continue-btn">Continue to Review</button></a>

            </div>
        </div>
        
        <!-- Order Summary Sidebar -->
        <aside class="order-summary">
            <h2>Order Summary</h2>
            
            <div class="summary-items">
                <?php if (!empty($cartItems)): ?>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="summary-item">
                            <img src="<?php echo htmlspecialchars($item['image_url'] ?? './images/product1.jpg'); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                            <div class="item-details">
                                <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                                <p class="item-variant"><?php echo htmlspecialchars($item['color'] ?? 'N/A'); ?> / <?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?></p>
                                <p class="item-quantity">Qty: <?php echo $item['quantity']; ?></p>
                            </div>
                            <span class="item-price">$<?php echo number_format($item['item_total'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; padding: 2rem; color: #666;">Your cart is empty</p>
                <?php endif; ?>
            </div>
            
            <div class="discount-code">
                <input type="text" placeholder="Discount code">
                <button class="apply-btn">Apply</button>
            </div>
            
            <div class="summary-pricing">
                <div class="price-row">
                    <span>Subtotal</span>
                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="price-row">
                    <span>Shipping</span>
                    <span id="shipping-cost"><?php echo $shipping_cost == 0 ? 'Free' : '$' . number_format($shipping_cost, 2); ?></span>
                </div>
                <div class="price-row">
                    <span>Tax (8%)</span>
                    <span>$<?php echo number_format($tax_amount, 2); ?></span>
                </div>
                <div class="price-row total-row">
                    <span>Total</span>
                    <span class="total-amount" id="order-total">$<?php echo number_format($total, 2); ?></span>
                </div>
            </div>
            
            <div class="secure-features">
                <p><i class='bx bx-lock-alt'></i> Secure payment processing</p>
                <p><i class='bx bx-refresh'></i> Free returns within 30 days</p>
                <p><i class='bx bx-check-circle'></i> 100% satisfaction guarantee</p>
            </div>
        </aside>
    </section>
    
    <script>
        // Update shipping cost and total when shipping method changes
        const subtotal = <?php echo $subtotal; ?>;
        const taxAmount = <?php echo $tax_amount; ?>;
        
        document.querySelectorAll('input[name="shippingMethod"]').forEach(radio => {
            radio.addEventListener('change', function() {
                let shippingCost = 0;
                
                switch(this.value) {
                    case 'standard':
                        shippingCost = 0;
                        break;
                    case 'express':
                        shippingCost = 15.00;
                        break;
                    case 'overnight':
                        shippingCost = 25.00;
                        break;
                }
                
                const total = subtotal + shippingCost + taxAmount;
                
                document.getElementById('shipping-cost').textContent = shippingCost === 0 ? 'Free' : '$' + shippingCost.toFixed(2);
                document.getElementById('order-total').textContent = '$' + total.toFixed(2);
            });
        });
    </script>
</main>
<?php include './inc/footer.php'; ?>
