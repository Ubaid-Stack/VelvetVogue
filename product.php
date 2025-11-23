<?php include './inc/header.php'; ?>
    
    <section class="product-detail-con">
        <!-- Breadcrumb Navigation -->
        <div class="back">
            <a href="shop.php"><span><i class='bx  bx-chevron-left'></i> Back to Shop</span></a>
        </div>

        <!-- Product Layout -->
        <div class="product-layout">
            <!-- Product Images -->
            <div class="product-images">
                <!-- Main Image -->
                <div class="main-image-container">
                    <span class="sale-badge">Sale 20%</span>
                    <img src="./images/product1.jpg" alt="Velvet Evening Dress" class="main-image" id="mainImage">
                </div>

                <!-- Thumbnail Images -->
                <div class="thumbnail-images">
                    <div class="thumbnail active" onclick="changeImage('./images/product1.jpg', this)">
                        <img src="./images/product1.jpg" alt="View 1">
                    </div>
                    <div class="thumbnail" onclick="changeImage('./images/product2.jpg', this)">
                        <img src="./images/product2.jpg" alt="View 2">
                    </div>
                    <div class="thumbnail" onclick="changeImage('./images/product3.jpg', this)">
                        <img src="./images/product3.jpg" alt="View 3">
                    </div>
                    <div class="thumbnail" onclick="changeImage('./images/product4.jpg', this)">
                        <img src="./images/product4.jpg" alt="View 4">
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <!-- Title -->
                <h1 class="product-title">Velvet Evening Dress</h1>

                <!-- Rating -->
                <div class="product-rating">
                    <div class="stars">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star empty'></i>
                    </div>
                    <span class="rating-text">4.7 (42 reviews)</span>
                </div>

                <!-- Price -->
                <div class="product-price">
                    <span class="price">$129.99</span>
                    <span class="original-price">$162.49</span>
                </div>

                <!-- Description -->
                <p class="product-description">
                    A stunning velvet evening dress designed to make a statement. This elegant piece features a fitted silhouette, V-neckline, and a subtle slit for movement. Perfect for formal events, galas, or any special occasion where you want to stand out.
                </p>

                <!-- Color Selection -->
                <div class="color-selection">
                    <label class="selection-label color-label">Color: <span>Black</span></label>
                    <div class="color-options">
                        <div class="color-option active" style="background-color: #000000;" onclick="selectColor(this, 'Black')"></div>
                        <div class="color-option" style="background-color: #8B1538;" onclick="selectColor(this, 'Maroon')"></div>
                        <div class="color-option" style="background-color: #000080;" onclick="selectColor(this, 'Navy')"></div>
                        <div class="color-option" style="background-color: #50C878;" onclick="selectColor(this, 'Emerald')"></div>
                    </div>
                </div>

                <!-- Size Selection -->
                <div class="size-selection">
                    <div class="size-header">
                        <label class="selection-label">Size: <span id="selectedSize">M</span></label>
                        <a href="#" class="size-guide-link">Size Guide</a>
                    </div>
                    <div class="size-options">
                        <button class="size-option" onclick="selectSize(this, 'XS')">XS</button>
                        <button class="size-option" onclick="selectSize(this, 'S')">S</button>
                        <button class="size-option active" onclick="selectSize(this, 'M')">M</button>
                        <button class="size-option" onclick="selectSize(this, 'L')">L</button>
                        <button class="size-option" onclick="selectSize(this, 'XL')">XL</button>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="quantity-section">
                    <label class="selection-label">Quantity:</label>
                    <div class="quantity-controls">
                        <div class="quantity-input">
                            <button class="qty-btn" onclick="decrementQty()">-</button>
                            <input type="text" class="qty-display" value="1" id="quantity" readonly>
                            <button class="qty-btn" onclick="incrementQty()">+</button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="add-to-cart-btn" onclick="addToCart()">Add to Cart</button>
                    <button class="buy-now-btn">Buy Now</button>
                </div>

                <!-- Secondary Actions -->
                <div class="secondary-actions">
                    <button class="wishlist-btn">
                        <i class='bx bx-heart'></i>
                        <span>Add to Wishlist</span>
                    </button>
                    <button class="share-btn">
                        <i class='bx bx-share-alt'></i>
                        <span>Share</span>
                    </button>
                </div>

                <!-- Product Meta -->
                <div class="product-meta">
                    <div class="meta-item">
                        <span class="meta-label">SKU:</span>
                        <span class="meta-value">VV-ED-1001</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Availability:</span>
                        <span class="meta-value availability in-stock">In Stock</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Categories:</span>
                        <span class="meta-value">Dresses, Evening Wear</span>
                    </div>
                </div>

                <!-- Product Features -->
                <div class="product-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class='bx bxs-truck'></i>
                        </div>
                        <div class="feature-text">
                            <h4>Free Shipping</h4>
                            <p>On orders over $100</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class='bx bx-reset'></i>
                        </div>
                        <div class="feature-text">
                            <h4>Easy Returns</h4>
                            <p>30-day return policy</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class='bx bxs-lock-alt'></i>
                        </div>
                        <div class="feature-text">
                            <h4>Secure Checkout</h4>
                            <p>SSL encrypted payment</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class='bx bxs-badge-check'></i>
                        </div>
                        <div class="feature-text">
                            <h4>Quality Guarantee</h4>
                            <p>Premium materials only</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs Section -->
        <div class="product-tabs-section">
            <div class="tabs-header">
                <button class="tab-btn active" onclick="openTab(event, 'description')"><span class="tab-text-full">Description</span><span class="tab-text-short">Info</span></button>
                <button class="tab-btn" onclick="openTab(event, 'materials')"><span class="tab-text-full">Materials & Care</span><span class="tab-text-short">Materials</span></button>
                <button class="tab-btn" onclick="openTab(event, 'reviews')"><span class="tab-text-full">Reviews (3)</span><span class="tab-text-short">Reviews</span></button>
            </div>

            <div id="description" class="tab-content active">
                <p>A stunning velvet evening dress designed to make a statement. This elegant piece features a fitted silhouette, V-neckline, and a subtle slit for movement. Perfect for formal events, galas, or any special occasion where you want to stand out.</p>
                
                <p>The velvet material adds a touch of luxury to this already stunning design. Its rich texture catches the light beautifully, creating a sophisticated look that's perfect for evening events.</p>
                
                <p>The dress features a comfortable stretch lining that ensures a perfect fit while maintaining the elegant drape of the outer velvet layer. The subtle side slit allows for ease of movement without compromising on style.</p>
            </div>

            <div id="materials" class="tab-content">
                <h3>Materials</h3>
                <ul>
                    <li>Outer: 95% Polyester, 5% Elastane</li>
                    <li>Lining: 100% Polyester</li>
                    <li>Soft velvet texture with stretch</li>
                </ul>

                <h3>Care Instructions</h3>
                <ul>
                    <li>Dry clean only</li>
                    <li>Do not bleach</li>
                    <li>Iron on low heat if needed</li>
                    <li>Store hanging to maintain shape</li>
                </ul>
            </div>

            <div id="reviews" class="tab-content">
                <div class="reviews-summary">
                    <div class="rating-overview">
                        <span class="rating-number">4.7</span>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star empty'></i>
                        </div>
                        <span class="reviews-count">Based on 42 reviews</span>
                    </div>
                </div>

                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <strong>Sarah M.</strong>
                            <div class="stars">
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                            </div>
                        </div>
                        <span class="review-date">2 weeks ago</span>
                    </div>
                    <p class="review-text">Absolutely stunning dress! The velvet is so luxurious and the fit is perfect. Got so many compliments at the gala.</p>
                </div>

                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <strong>Emily R.</strong>
                            <div class="stars">
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star empty'></i>
                            </div>
                        </div>
                        <span class="review-date">1 month ago</span>
                    </div>
                    <p class="review-text">Beautiful dress, true to size. The quality is excellent. Only wish it came in more colors!</p>
                </div>

                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <strong>Jessica L.</strong>
                            <div class="stars">
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                            </div>
                        </div>
                        <span class="review-date">2 months ago</span>
                    </div>
                    <p class="review-text">Perfect for a special occasion! The material feels premium and the cut is very flattering.</p>
                </div>
                <!-- <a href="writeReview.php"><button class="review-btn">Write a Review</button></a> -->
            </div>
        </div>

        <!-- You May Also Like Section -->
        <div class="related-products-section">
            <h2 class="section-title">You May Also Like</h2>
            <div class="related-products-grid">
                <div class="related-product-card">
                    <div class="product-badge sale">SALE</div>
                    <button class="wishlist-icon"><i class='bx bx-heart'></i></button>
                    <a href="product.php">
                        <img src="./images/product2.jpg" alt="Denim Jacket">
                        <h3>Classic Denim Jacket</h3>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                        <div class="product-price-info">
                            <span class="current-price">$89.99</span>
                            <span class="old-price">$120.00</span>
                        </div>
                    </a>
                </div>

                <div class="related-product-card">
                    <button class="wishlist-icon"><i class='bx bx-heart'></i></button>
                    <a href="product.php">
                        <img src="./images/product3.jpg" alt="Silk Blouse">
                        <h3>Silk Summer Blouse</h3>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star empty'></i>
                        </div>
                        <div class="product-price-info">
                            <span class="current-price">$65.00</span>
                        </div>
                    </a>
                </div>

                <div class="related-product-card">
                    <button class="wishlist-icon"><i class='bx bx-heart'></i></button>
                    <a href="product.php">
                        <img src="./images/product4.jpg" alt="Formal Blazer">
                        <h3>Tailored Formal Blazer</h3>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                        <div class="product-price-info">
                            <span class="current-price">$145.00</span>
                        </div>
                    </a>
                </div>

                <div class="related-product-card">
                    <div class="product-badge sale">SALE</div>
                    <button class="wishlist-icon"><i class='bx bx-heart'></i></button>
                    <a href="product.php">
                        <img src="./images/product1.jpg" alt="Original T-Shirt">
                        <h3>Original Graphic Tee</h3>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star empty'></i>
                        </div>
                        <div class="product-price-info">
                            <span class="current-price">$29.99</span>
                            <span class="old-price">$45.00</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Change main image on thumbnail click
        function changeImage(imageSrc, element) {
            document.getElementById('mainImage').src = imageSrc;
            
            // Remove active class from all thumbnails
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            // Add active class to clicked thumbnail
            element.classList.add('active');
        }

        // Select color
        function selectColor(element, colorName) {
            // Remove active class from all colors
            document.querySelectorAll('.color-option').forEach(color => {
                color.classList.remove('active');
            });
            
            // Add active class to selected color
            element.classList.add('active');
            
            // Update color text
            document.querySelector('.color-label span').textContent = colorName;
        }

        // Select size
        function selectSize(element, size) {
            // Remove active class from all sizes
            document.querySelectorAll('.size-option').forEach(sizeBtn => {
                sizeBtn.classList.remove('active');
            });
            
            // Add active class to selected size
            element.classList.add('active');
            
            // Update size text
            document.getElementById('selectedSize').textContent = size;
        }

        // Increment quantity
        function incrementQty() {
            let qtyInput = document.getElementById('quantity');
            let currentQty = parseInt(qtyInput.value);
            qtyInput.value = currentQty + 1;
        }

        // Decrement quantity
        function decrementQty() {
            let qtyInput = document.getElementById('quantity');
            let currentQty = parseInt(qtyInput.value);
            if (currentQty > 1) {
                qtyInput.value = currentQty - 1;
            }
        }

        // Add to cart with SweetAlert
        function addToCart() {
            Swal.fire({
                icon: 'success',
                title: 'Added to Cart!',
                text: 'Product has been added to your cart',
                showConfirmButton: false,
                timer: 1500,
                toast: true,
                position: 'top-end'
            });
        }

        // Tab functionality
        function openTab(evt, tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all buttons
            const tabBtns = document.querySelectorAll('.tab-btn');
            tabBtns.forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab and mark button as active
            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }
    </script>

<?php include './inc/footer.php'; ?>