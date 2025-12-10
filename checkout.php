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
                <h2 class="title"><i class='bx bx-credit-card-front'></i> Payment Method</h2>
                <div class="payment-option">
                    <input type="radio" id="creditCard" name="paymentMethod" value="credit_card" checked>
                    <label for="creditCard">
                        <span class="method-name">💳 Credit/Debit Card</span>
                        <span class="method-details">We accept Visa, MasterCard, American Express, and Discover.</span>
                    </label>
                </div>
                <div class="payment-option">
                    <input type="radio" id="paypal" name="paymentMethod" value="paypal">
                    <label for="paypal">
                        <span class="method-name"><i class='bx bxl-paypal' style="font-size: 18px; vertical-align: middle;"></i> PayPal</span>
                        <span class="method-details">Secure payment through your PayPal account.</span>
                    </label>
                </div>
                
                <!-- Credit Card Fields -->
                <div id="creditCardFields">
                    <div>
                        <label for="cardNumber">Card Number</label>
                        <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                        <!-- <div class="card-brands" style="margin-top: 8px;">
                            <span style="font-size: 11px; color: #6c757d;">We accept:</span>
                            <span style="font-size: 18px;">💳</span>
                            <span style="font-size: 11px; color: #1a1f71; font-weight: 600;">VISA</span>
                            <span style="font-size: 11px; color: #eb001b; font-weight: 600;">MC</span>
                            <span style="font-size: 11px; color: #006fcf; font-weight: 600;">AMEX</span>
                            <span style="font-size: 11px; color: #ff6000; font-weight: 600;">DISC</span>
                        </div> -->
                    </div>

                    <div>
                        <label for="nameOnCard">Name on Card</label>
                        <input type="text" id="nameOnCard" name="nameOnCard" placeholder="Ubaidullah">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <label for="expiryDate">Expiry Date</label>
                            <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY" maxlength="5">
                        </div>
                        <div>
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4">
                            <span style="font-size: 11px; color: #6c757d; margin-top: 4px; display: block;">3-4 digits on back</span>
                        </div>
                    </div>
                </div>
                
                <!-- PayPal Fields -->
                <div id="paypalFields" style="display: none;">
                    <div>
                        <label for="paypalEmail">PayPal Email Address</label>
                        <input type="email" id="paypalEmail" name="paypalEmail" placeholder="your-email@example.com">
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%); padding: 16px; border-radius: 12px; margin-top: 12px; border-left: 4px solid #0070ba; box-shadow: 0 2px 8px rgba(0, 112, 186, 0.1);">
                        <p style="margin: 0; font-size: 14px; color: #495057; line-height: 1.6; display: flex; align-items: flex-start; gap: 10px;">
                            <i class='bx bxl-paypal' style="color: #0070ba; font-size: 24px; margin-top: 2px;"></i>
                            <span>After clicking "Continue to Review", you'll be able to complete your payment securely through PayPal on the next page.</span>
                        </p>
                    </div>
                </div>

                <button type="button" class="continue-btn" id="continueBtn">Continue to Review</button>

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
        
        // Toggle payment method fields
        const creditCardFields = document.getElementById('creditCardFields');
        const paypalFields = document.getElementById('paypalFields');
        const paymentMethodRadios = document.querySelectorAll('input[name="paymentMethod"]');
        
        paymentMethodRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'credit_card') {
                    creditCardFields.style.display = 'block';
                    paypalFields.style.display = 'none';
                    
                    // Set required for credit card fields
                    document.getElementById('cardNumber').required = true;
                    document.getElementById('nameOnCard').required = true;
                    document.getElementById('expiryDate').required = true;
                    document.getElementById('cvv').required = true;
                    document.getElementById('paypalEmail').required = false;
                } else if (this.value === 'paypal') {
                    creditCardFields.style.display = 'none';
                    paypalFields.style.display = 'block';
                    
                    // Set required for PayPal fields
                    document.getElementById('cardNumber').required = false;
                    document.getElementById('nameOnCard').required = false;
                    document.getElementById('expiryDate').required = false;
                    document.getElementById('cvv').required = false;
                    document.getElementById('paypalEmail').required = true;
                }
            });
        });
        
        // Format card number with spaces
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
        
        // Format expiry date
        document.getElementById('expiryDate').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });
        
        // Continue to Review button
        document.getElementById('continueBtn').addEventListener('click', function() {
            // Validate all required fields
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const address = document.getElementById('address').value.trim();
            const city = document.getElementById('city').value.trim();
            const state = document.getElementById('state').value.trim();
            const zip = document.getElementById('zip').value.trim();
            const shippingMethod = document.querySelector('input[name="shippingMethod"]:checked').value;
            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            
            if (!email || !phone || !firstName || !lastName || !address || !city || !state || !zip) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Please fill in all required fields',
                    confirmButtonColor: '#3C91E6'
                });
                return;
            }
            
            // Validate payment fields based on selected method
            if (paymentMethod === 'credit_card') {
                const cardNumber = document.getElementById('cardNumber').value.trim();
                const nameOnCard = document.getElementById('nameOnCard').value.trim();
                const expiryDate = document.getElementById('expiryDate').value.trim();
                const cvv = document.getElementById('cvv').value.trim();
                
                if (!cardNumber || !nameOnCard || !expiryDate || !cvv) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Payment Information',
                        text: 'Please fill in all credit card details',
                        confirmButtonColor: '#3C91E6'
                    });
                    return;
                }
            } else if (paymentMethod === 'paypal') {
                const paypalEmail = document.getElementById('paypalEmail').value.trim();
                
                if (!paypalEmail) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing PayPal Email',
                        text: 'Please enter your PayPal email address',
                        confirmButtonColor: '#3C91E6'
                    });
                    return;
                }
            }
            
            // Save data to session via AJAX
            const formData = new FormData();
            formData.append('save_checkout_data', '1');
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('firstName', firstName);
            formData.append('lastName', lastName);
            formData.append('address', address);
            formData.append('apartment', document.getElementById('apartment').value.trim());
            formData.append('city', city);
            formData.append('state', state);
            formData.append('zip', zip);
            formData.append('shippingMethod', shippingMethod);
            formData.append('paymentMethod', paymentMethod);
            
            if (paymentMethod === 'credit_card') {
                formData.append('cardNumber', document.getElementById('cardNumber').value.trim());
                formData.append('nameOnCard', document.getElementById('nameOnCard').value.trim());
                formData.append('expiryDate', document.getElementById('expiryDate').value.trim());
                formData.append('cvv', document.getElementById('cvv').value.trim());
            } else if (paymentMethod === 'paypal') {
                formData.append('paypalEmail', document.getElementById('paypalEmail').value.trim());
            }
            
            fetch('save_checkout_session.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'finalReview.php';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to save checkout data',
                        confirmButtonColor: '#EF4444'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#EF4444'
                });
            });
        });
    </script>
</main>
<?php include './inc/footer.php'; ?>
