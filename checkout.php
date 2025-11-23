<?php include './inc/header.php'; ?>
   
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
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required>

                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>

                    <span class="">Already have an account? <a href="#">Log in</a></span>
                </form>
            </div>
            <div class="shipping-address">
                <form class="shipping-form" method="post" action="">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" required>

                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" required>

                    <label for="apartment">Apartment, suite, etc. (optional):</label>
                    <input type="text" id="apartment" name="apartment" placeholder="Enter your apartment number">

                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" placeholder="Enter your city" required>

                    <label for="state">State/Province:</label>
                    <input type="text" id="state" name="state" placeholder="Enter your state or province" required>

                    <label for="zip">ZIP/Postal Code:</label>
                    <input type="text" id="zip" name="zip" placeholder="Enter your ZIP or postal code" required>


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
                        <input type="radio" id="express" name="shippingMethod" value="express">
                        <label for="express">
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
                <div class="summary-item">
                    <img src="./images/product1.jpg" alt="Velvet Evening Dress">
                    <div class="item-details">
                        <h4>Velvet Evening Dress</h4>
                        <p class="item-variant">Black / M</p>
                    </div>
                    <span class="item-price">$129.99</span>
                </div>
                
                <div class="summary-item">
                    <img src="./images/product2.jpg" alt="Classic Fitted Blazer">
                    <div class="item-details">
                        <h4>Classic Fitted Blazer</h4>
                        <p class="item-variant">Navy / L</p>
                    </div>
                    <span class="item-price">$139.98</span>
                </div>
            </div>
            
            <div class="discount-code">
                <input type="text" placeholder="Discount code">
                <button class="apply-btn">Apply</button>
            </div>
            
            <div class="summary-pricing">
                <div class="price-row">
                    <span>Subtotal</span>
                    <span>$269.97</span>
                </div>
                <div class="price-row">
                    <span>Shipping (Standard Shipping)</span>
                    <span>$5.99</span>
                </div>
                <div class="price-row">
                    <span>Tax (8%)</span>
                    <span>$21.60</span>
                </div>
                <div class="price-row total-row">
                    <span>Total</span>
                    <span class="total-amount">$297.56</span>
                </div>
            </div>
            
            <div class="secure-features">
                <p><i class='bx bx-lock-alt'></i> Secure payment processing</p>
                <p><i class='bx bx-refresh'></i> Free returns within 30 days</p>
                <p><i class='bx bx-check-circle'></i> 100% satisfaction guarantee</p>
            </div>
        </aside>
    </section>
</main>
<?php include './inc/footer.php'; ?>
