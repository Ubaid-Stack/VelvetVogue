<?php 
session_start();
include './inc/db.php';
include './inc/header.php'; 

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header('Location: shop.php');
    exit();
}

// Fetch product details
$query = "SELECT p.*, c.category_name FROM products p 
          LEFT JOIN categories c ON p.category_id = c.category_id 
          WHERE p.product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: shop.php');
    exit();
}

$product = $result->fetch_assoc();
$stmt->close();

// Fetch product images
$imgQuery = "SELECT image_url, is_primary FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, image_id ASC";
$imgStmt = $conn->prepare($imgQuery);
$imgStmt->bind_param("i", $product_id);
$imgStmt->execute();
$imgResult = $imgStmt->get_result();
$images = [];
while ($img = $imgResult->fetch_assoc()) {
    // Fix image path - convert ../images/ to ./images/ for frontend
    $img['image_url'] = str_replace('../images/', './images/', $img['image_url']);
    $images[] = $img;
}
$imgStmt->close();

$primary_image = !empty($images) ? $images[0]['image_url'] : './images/product1.jpg';

// Fetch product variants with color_hex
$columns = "variant_id, size, color, additional_price, stock_quantity, sku, is_available";
$checkColumn = $conn->query("SHOW COLUMNS FROM product_variants LIKE 'color_hex'");
if ($checkColumn && $checkColumn->num_rows > 0) {
    $columns = "variant_id, size, color, color_hex, additional_price, stock_quantity, sku, is_available";
}

$variantQuery = "SELECT {$columns} FROM product_variants WHERE product_id = ? AND is_available = 1 ORDER BY size, color";
$variantStmt = $conn->prepare($variantQuery);
$variantStmt->bind_param("i", $product_id);
$variantStmt->execute();
$variantResult = $variantStmt->get_result();
$variants = [];
$colors = [];
$sizes = [];
while ($variant = $variantResult->fetch_assoc()) {
    if (!isset($variant['color_hex'])) {
        $variant['color_hex'] = '#000000';
    }
    $variants[] = $variant;
    
    // Collect unique colors
    $colorKey = $variant['color'] . '|' . $variant['color_hex'];
    if (!isset($colors[$colorKey])) {
        $colors[$colorKey] = [
            'name' => $variant['color'],
            'hex' => $variant['color_hex']
        ];
    }
    
    // Collect unique sizes
    if (!in_array($variant['size'], $sizes)) {
        $sizes[] = $variant['size'];
    }
}
$variantStmt->close();

// Sort sizes in proper order
$size_order = ['XS' => 1, 'S' => 2, 'M' => 3, 'L' => 4, 'XL' => 5, 'XXL' => 6];
usort($sizes, function($a, $b) use ($size_order) {
    $order_a = $size_order[$a] ?? 99;
    $order_b = $size_order[$b] ?? 99;
    return $order_a - $order_b;
});

// Fetch reviews and ratings
$reviewQuery = "SELECT AVG(rating) as avg_rating, COUNT(*) as review_count FROM reviews WHERE product_id = ?";
$reviewStmt = $conn->prepare($reviewQuery);
$reviewStmt->bind_param("i", $product_id);
$reviewStmt->execute();
$reviewResult = $reviewStmt->get_result();
$reviewData = $reviewResult->fetch_assoc();
$reviewStmt->close();

$avg_rating = $reviewData['avg_rating'] ? round($reviewData['avg_rating'], 1) : 0;
$review_count = $reviewData['review_count'] ? $reviewData['review_count'] : 0;

// Fetch individual reviews
$reviewsQuery = "SELECT r.*, u.full_name, u.username, r.created_at 
                 FROM reviews r 
                 LEFT JOIN users u ON r.user_id = u.user_id 
                 WHERE r.product_id = ? 
                 ORDER BY r.created_at DESC 
                 LIMIT 10";
$reviewsStmt = $conn->prepare($reviewsQuery);
$reviewsStmt->bind_param("i", $product_id);
$reviewsStmt->execute();
$reviewsResult = $reviewsStmt->get_result();
$reviews = [];
while ($review = $reviewsResult->fetch_assoc()) {
    $reviews[] = $review;
}
$reviewsStmt->close();

// Calculate discount percentage and set display price
$discount_percent = 0;
$current_price = $product['price'];
$display_original_price = $product['original_price'] ?? $product['price'];

if ($display_original_price > $current_price) {
    $discount_percent = round((($display_original_price - $current_price) / $display_original_price) * 100);
}

// Check stock availability
$total_stock = array_sum(array_column($variants, 'stock_quantity'));
$is_in_stock = $total_stock > 0;
?>
    
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
                    <?php if ($discount_percent > 0): ?>
                        <span class="sale-badge">Sale <?php echo $discount_percent; ?>%</span>
                    <?php endif; ?>
                    <img src="<?php echo htmlspecialchars($primary_image); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="main-image" id="mainImage">
                </div>

                <!-- Thumbnail Images -->
                <div class="thumbnail-images">
                    <?php 
                    $activeClass = 'active';
                    foreach ($images as $index => $image): 
                    ?>
                    <div class="thumbnail <?php echo $activeClass; ?>" onclick="changeImage('<?php echo htmlspecialchars($image['image_url']); ?>', this)">
                        <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="View <?php echo $index + 1; ?>">
                    </div>
                    <?php 
                    $activeClass = ''; // Only first image is active
                    endforeach; 
                    ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <!-- Title -->
                <h1 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h1>

                <!-- Rating -->
                <div class="product-rating">
                    <div class="stars">
                        <?php
                        $fullStars = floor($avg_rating);
                        $halfStar = ($avg_rating - $fullStars) >= 0.5;
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        
                        for ($i = 0; $i < $fullStars; $i++) {
                            echo '<i class="bx bxs-star"></i>';
                        }
                        if ($halfStar) {
                            echo '<i class="bx bxs-star-half"></i>';
                        }
                        for ($i = 0; $i < $emptyStars; $i++) {
                            echo '<i class="bx bxs-star empty"></i>';
                        }
                        ?>
                    </div>
                    <span class="rating-text"><?php echo $avg_rating; ?> (<?php echo $review_count; ?> reviews)</span>
                </div>

                <!-- Price -->
                <div class="product-price">
                    <span class="price" id="currentPrice">$<?php echo number_format($current_price, 2); ?></span>
                    <?php if ($discount_percent > 0): ?>
                        <span class="original-price">$<?php echo number_format($display_original_price, 2); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <p class="product-description">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </p>

                <!-- Color Selection -->
                <?php if (!empty($colors)): ?>
                <div class="color-selection">
                    <label class="selection-label color-label">Color: <span id="selectedColorName"><?php echo htmlspecialchars(reset($colors)['name']); ?></span></label>
                    <div class="color-options">
                        <?php 
                        $firstColor = true;
                        foreach ($colors as $color): 
                        ?>
                        <div class="color-option <?php echo $firstColor ? 'active' : ''; ?>" 
                             style="background-color: <?php echo htmlspecialchars($color['hex']); ?>;" 
                             data-color="<?php echo htmlspecialchars($color['name']); ?>"
                             data-hex="<?php echo htmlspecialchars($color['hex']); ?>"
                             onclick="selectColor(this)"></div>
                        <?php 
                        $firstColor = false;
                        endforeach; 
                        ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Size Selection -->
                <?php if (!empty($sizes)): ?>
                <div class="size-selection">
                    <div class="size-header">
                        <label class="selection-label">Size: <span id="selectedSize"><?php echo htmlspecialchars($sizes[0]); ?></span></label>
                        <a href="#" class="size-guide-link">Size Guide</a>
                    </div>
                    <div class="size-options">
                        <?php 
                        $firstSize = true;
                        foreach ($sizes as $size): 
                        ?>
                        <button class="size-option <?php echo $firstSize ? 'active' : ''; ?>" 
                                data-size="<?php echo htmlspecialchars($size); ?>"
                                onclick="selectSize(this)"><?php echo htmlspecialchars($size); ?></button>
                        <?php 
                        $firstSize = false;
                        endforeach; 
                        ?>
                    </div>
                </div>
                <?php endif; ?>

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
                    <button class="add-to-cart-btn" onclick="addToCart(<?php echo $product_id; ?>)" <?php echo !$is_in_stock ? 'disabled' : ''; ?>>
                        <?php echo $is_in_stock ? 'Add to Cart' : 'Out of Stock'; ?>
                    </button>
                    <button class="buy-now-btn" <?php echo !$is_in_stock ? 'disabled' : ''; ?>>Buy Now</button>
                </div>

                <!-- Secondary Actions -->
                <div class="secondary-actions">
                    <button class="wishlist-btn" onclick="toggleWishlist(<?php echo $product_id; ?>, this)">
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
                        <span class="meta-value" id="currentSKU"><?php echo !empty($variants) ? htmlspecialchars($variants[0]['sku']) : 'N/A'; ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Availability:</span>
                        <span class="meta-value availability <?php echo $is_in_stock ? 'in-stock' : 'out-of-stock'; ?>" id="availabilityStatus">
                            <?php echo $is_in_stock ? 'In Stock' : 'Out of Stock'; ?>
                        </span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Categories:</span>
                        <span class="meta-value"><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></span>
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
                <button class="tab-btn" onclick="openTab(event, 'reviews')"><span class="tab-text-full">Reviews (<?php echo $review_count; ?>)</span><span class="tab-text-short">Reviews</span></button>
            </div>

            <div id="description" class="tab-content active">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </div>

            <div id="reviews" class="tab-content">
                <div class="reviews-summary">
                    <div class="rating-overview">
                        <span class="rating-number"><?php echo $avg_rating; ?></span>
                        <div class="stars">
                            <?php
                            $fullStars = floor($avg_rating);
                            $halfStar = ($avg_rating - $fullStars) >= 0.5;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            
                            for ($i = 0; $i < $fullStars; $i++) {
                                echo '<i class="bx bxs-star"></i>';
                            }
                            if ($halfStar) {
                                echo '<i class="bx bxs-star-half"></i>';
                            }
                            for ($i = 0; $i < $emptyStars; $i++) {
                                echo '<i class="bx bxs-star empty"></i>';
                            }
                            ?>
                        </div>
                        <span class="reviews-count">Based on <?php echo $review_count; ?> reviews</span>
                    </div>
                </div>

                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <strong><?php 
                                        // Display full name or username with privacy
                                        $displayName = $review['full_name'] ?? $review['username'] ?? 'Anonymous';
                                        if ($review['full_name']) {
                                            $nameParts = explode(' ', $review['full_name']);
                                            if (count($nameParts) > 1) {
                                                echo htmlspecialchars($nameParts[0] . ' ' . substr($nameParts[1], 0, 1) . '.');
                                            } else {
                                                echo htmlspecialchars($review['full_name']);
                                            }
                                        } else {
                                            echo htmlspecialchars($review['username']);
                                        }
                                    ?></strong>
                                    <div class="stars">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $review['rating']) {
                                                echo '<i class="bx bxs-star"></i>';
                                            } else {
                                                echo '<i class="bx bxs-star empty"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <span class="review-date"><?php 
                                    $reviewDate = new DateTime($review['created_at']);
                                    $now = new DateTime();
                                    $diff = $now->diff($reviewDate);
                                    
                                    if ($diff->y > 0) {
                                        echo $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
                                    } elseif ($diff->m > 0) {
                                        echo $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
                                    } elseif ($diff->d > 0) {
                                        echo $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
                                    } elseif ($diff->h > 0) {
                                        echo $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
                                    } else {
                                        echo 'Just now';
                                    }
                                ?></span>
                            </div>
                            <p class="review-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; padding: 2rem; color: #666;">No reviews yet. Be the first to review this product!</p>
                <?php endif; ?>
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
</main>
    <script>
        // Product variants data
        const variants = <?php echo json_encode($variants); ?>;
        const basePrice = <?php echo $current_price; ?>;
        
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
        function selectColor(element) {
            // Remove active class from all colors
            document.querySelectorAll('.color-option').forEach(color => {
                color.classList.remove('active');
            });
            
            // Add active class to selected color
            element.classList.add('active');
            
            // Update color text
            const colorName = element.dataset.color;
            document.getElementById('selectedColorName').textContent = colorName;
            
            // Update variant info
            updateVariantInfo();
        }

        // Select size
        function selectSize(element) {
            // Remove active class from all sizes
            document.querySelectorAll('.size-option').forEach(sizeBtn => {
                sizeBtn.classList.remove('active');
            });
            
            // Add active class to selected size
            element.classList.add('active');
            
            // Update size text
            const size = element.dataset.size;
            document.getElementById('selectedSize').textContent = size;
            
            // Update variant info
            updateVariantInfo();
        }
        
        // Update variant info based on selected color and size
        function updateVariantInfo() {
            const selectedColor = document.querySelector('.color-option.active')?.dataset.color;
            const selectedSize = document.querySelector('.size-option.active')?.dataset.size;
            
            console.log('Selected color:', selectedColor, 'Selected size:', selectedSize);
            
            if (!selectedColor || !selectedSize) return;
            
            // Find matching variant
            const variant = variants.find(v => v.color === selectedColor && v.size === selectedSize);
            
            console.log('Found variant:', variant);
            
            if (variant) {
                // Update price
                const variantPrice = basePrice + parseFloat(variant.additional_price || 0);
                document.getElementById('currentPrice').textContent = '$' + variantPrice.toFixed(2);
                
                // Update SKU
                document.getElementById('currentSKU').textContent = variant.sku || 'N/A';
                
                // Update availability
                const availabilityEl = document.getElementById('availabilityStatus');
                const addToCartBtn = document.querySelector('.add-to-cart-btn');
                const buyNowBtn = document.querySelector('.buy-now-btn');
                
                const stockQty = parseInt(variant.stock_quantity) || 0;
                console.log('Stock quantity:', stockQty);
                
                if (stockQty > 0) {
                    availabilityEl.textContent = 'In Stock';
                    availabilityEl.className = 'meta-value availability in-stock';
                    addToCartBtn.disabled = false;
                    addToCartBtn.textContent = 'Add to Cart';
                    buyNowBtn.disabled = false;
                } else {
                    availabilityEl.textContent = 'Out of Stock';
                    availabilityEl.className = 'meta-value availability out-of-stock';
                    addToCartBtn.disabled = true;
                    addToCartBtn.textContent = 'Out of Stock';
                    buyNowBtn.disabled = true;
                }
            }
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

        // Add to cart with AJAX
        function addToCart(productId) {
            <?php if (!isset($_SESSION['user_id'])): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Login Required',
                text: 'Please login to add items to cart',
                showCancelButton: true,
                confirmButtonText: 'Login',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'login.php';
                }
            });
            return;
            <?php endif; ?>
            
            const quantity = parseInt(document.getElementById('quantity').value);
            const selectedColor = document.querySelector('.color-option.active')?.dataset.color;
            const selectedSize = document.querySelector('.size-option.active')?.dataset.size;
            
            // Find matching variant
            const variant = variants.find(v => v.color === selectedColor && v.size === selectedSize);
            
            if (!variant) {
                Swal.fire({
                    icon: 'error',
                    title: 'Selection Required',
                    text: 'Please select color and size',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }
            
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&variant_id=${variant.variant_id}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart!',
                        text: 'Product has been added to your cart',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true,
                        position: 'top-end'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to add to cart',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        }
        
        // Toggle wishlist
        function toggleWishlist(productId, element) {
            <?php if (!isset($_SESSION['user_id'])): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Login Required',
                text: 'Please login to add items to wishlist',
                showCancelButton: true,
                confirmButtonText: 'Login',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'login.php';
                }
            });
            return;
            <?php endif; ?>
            
            fetch('add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const icon = element.querySelector('i');
                    const text = element.querySelector('span');
                    
                    if (data.action === 'added') {
                        icon.className = 'bx bxs-heart';
                        text.textContent = 'Added to Wishlist';
                        Swal.fire({
                            icon: 'success',
                            title: 'Added to Wishlist!',
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true,
                            position: 'top-end'
                        });
                    } else {
                        icon.className = 'bx bx-heart';
                        text.textContent = 'Add to Wishlist';
                        Swal.fire({
                            icon: 'info',
                            title: 'Removed from Wishlist',
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to update wishlist',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
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
        
        // Initialize variant info on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Variants loaded:', variants);
            updateVariantInfo();
        });
    </script>

<?php include './inc/footer.php'; ?>