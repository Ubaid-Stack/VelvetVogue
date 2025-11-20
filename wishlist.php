<?php include 'inc/header.php'; ?>
    </main>
    
    <!-- Wishlist page content outside body-con wrapper -->
    <section class="wishlist-con">
        <h2 class="wishlist-title">My Wishlist</h2>
        <p class="wishlist-subtext">Here are the items you've added to your wishlist.</p>
        <div class="wishlist-items">
            <!-- Example wishlist item -->
            <div class="wishlist-item">
                <img src="./images/product1.jpg" alt="Product 1">
                <div class="item-details">
                    <h4>Elegant Red Dress</h4>
                    <p>$79.99</p>
                    <button class="remove-btn">Remove</button>
                </div>
            </div>
            <div class="wishlist-item">
                <img src="./images/product2.jpg" alt="Product 2">
                <div class="item-details">
                    <h4>Classic Blue Jeans</h4>
                    <p>$49.99</p>
                    <button class="remove-btn">Remove</button>
                </div>
            </div>
        </div>
    </section>

<?php include 'inc/footer.php'; ?>