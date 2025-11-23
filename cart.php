<?php include 'inc/header.php'; ?>
    </main>

    <!-- Cart page content outside body-con wrapper -->
    <section class="cart-con">
        <div class="about-title">
            <h2 class="cart-title">My Shopping Cart</h2>
            <p class="cart-subtext">Review the items in your cart before proceeding to checkout.</p>
        </div>
        <div class="cart-items">
            <!-- Example cart item -->
            <div class="cart-item">
                <img src="./images/product1.jpg" alt="Product 1">
                <div class="item-info">
                    <h4>Elegant Red Dress</h4>
                    <p class="item-meta">Color: Red | Size: M</p>
                </div>
                <div class="qty-controls">
                    <button class="dec-btn">-</button>
                    <span class="item-quantity">1</span>
                    <button class="inc-btn">+</button>
                </div>
                <div class="item-pricing">
                    <p class="unit-price">$79.99</p>
                    <p class="total-price">$79.99</p>
                </div>
                <button class="remove-btn"><i class='bx  bx-trash'></i></button>
            </div>
            <div class="cart-item">
                <img src="./images/product2.jpg" alt="Product 2">
                <div class="item-info">
                    <h4>Classic Blue Jeans</h4>
                    <p class="item-meta">Color: Blue | Size: L</p>
                </div>
                <div class="qty-controls">
                    <button class="dec-btn">-</button>
                    <span class="item-quantity">2</span>
                    <button class="inc-btn">+</button>
                </div>
                <div class="item-pricing">
                    <p class="unit-price">$49.99</p>
                    <p class="total-price">$99.98</p>
                </div>
                <button class="remove-btn"><i class='bx  bx-trash'></i></button>
            </div>
        </div>
        <div class="cart-summary">
            <h3>Cart Summary</h3>
            <p>Total Items: <span>3</span></p>
            <p>Subtotal: <span>$179.97</span></p>
            <p>Shipping: <span>Free</span></p>
            <p>Total Price: <span>$179.97</span></p>
            <a href="checkout.php"><button class="checkout-btn">Proceed to Checkout</button></a>
        </div>
    </section>

    <script>
        document.querySelectorAll('.remove-btn').forEach(button => {
            button.addEventListener('click', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Removed from Cart!',
                    text: 'Item has been removed from your cart',
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true,
                    position: 'top-end'

                });
                
                // // Remove the cart item from DOM
                // this.closest('.cart-item').remove();
            });
        });
    </script>
<?php include 'inc/footer.php'; ?>