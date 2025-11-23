<?php include './inc/header.php'; ?>
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
                    <p><strong>Email:</strong> ubaidullah2003@gmail.com</p>
                    <p><strong>Phone:</strong> 0763424376</p>
                </div>
            </div>
            
            <!-- Shipping Address -->
            <div class="review-section">
                <div class="section-header">
                    <h3>Shipping Address</h3>
                    <a href="checkout.php" class="edit-link"><i class='bx bx-edit-alt'></i> Edit</a>
                </div>
                <div class="section-content">
                    <p>Ubaidullah Fareed</p>
                    <p>Ubaidullah</p>
                    <p>123 Main St</p>
                    <p>Sarvodaya Road Eravur</p>
                    <p>Sri Lanka</p>
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
                            <p class="method-name">Standard Shipping</p>
                            <p class="method-time">5-7 business days</p>
                        </div>
                        <span class="method-price">$5.99</span>
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
                    <p><strong>Credit Card</strong></p>
                    <p>•••• •••• •••• 2345</p>
                    <p>324</p>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="review-section order-items-section">
                <h3>Order Items</h3>
                <div class="order-items-list">
                    <div class="order-item">
                        <img src="./images/product1.jpg" alt="Product">
                        <div class="item-info">
                            <h4>Velvet Evening Dress</h4>
                            <p class="item-variant">Black / M</p>
                            <p class="item-quantity">Qty: 1</p>
                        </div>
                        <span class="item-price">$129.99</span>
                    </div>
                    <div class="order-item">
                        <img src="./images/product2.jpg" alt="Product">
                        <div class="item-info">
                            <h4>Classic Fitted Blazer</h4>
                            <p class="item-variant">Navy / L</p>
                            <p class="item-quantity">Qty: 1</p>
                        </div>
                        <span class="item-price">$139.98</span>
                    </div>
                </div>
            </div>
            
            <button class="place-order-btn">Place Order</button>
        </div>    </section>

    <script>
        document.querySelector('.place-order-btn').addEventListener('click', function() {
            Swal.fire({
                icon: 'success',
                title: 'Order Placed Successfully!',
                text: 'Thank you for your purchase. Your order is being processed.',
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                position: 'top-end'
            }).then(() => {
                // Redirect to order confirmation page
                // window.location.href = 'order-confirmation.php';
            });
        });
    </script>
<?php include './inc/footer.php'; ?>
