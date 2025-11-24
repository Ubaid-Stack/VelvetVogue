<?php 
require_once './inc/db.php';

// Pagination settings
$products_per_page = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $products_per_page;

// Filter parameters
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$price_max = isset($_GET['price']) ? intval($_GET['price']) : 10000;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query
$where_conditions = ["p.status = 'active'"];
$params = [];
$types = '';

if (!empty($category_filter)) {
    $where_conditions[] = "c.category_id = ?";
    $params[] = $category_filter;
    $types .= 'i';
}

if ($price_max < 10000) {
    $where_conditions[] = "p.price <= ?";
    $params[] = $price_max;
    $types .= 'd';
}

if (!empty($search)) {
    $where_conditions[] = "(p.product_name LIKE ? OR p.description LIKE ?)";
    $search_param = "%{$search}%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
}

$where_sql = implode(' AND ', $where_conditions);

// Count total products
$count_sql = "SELECT COUNT(DISTINCT p.product_id) as total 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.category_id 
              WHERE {$where_sql}";

$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_products = $count_stmt->get_result()->fetch_assoc()['total'];
$count_stmt->close();

$total_pages = ceil($total_products / $products_per_page);

// Fetch products
$sql = "SELECT p.*, c.category_name, 
        pi.image_url,
        COALESCE(AVG(r.rating), 0) as avg_rating,
        COUNT(DISTINCT r.review_id) as review_count
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
        LEFT JOIN reviews r ON p.product_id = r.product_id AND r.is_approved = 1
        WHERE {$where_sql}
        GROUP BY p.product_id
        ORDER BY p.created_at DESC
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$params[] = $products_per_page;
$params[] = $offset;
$types .= 'ii';
$stmt->bind_param($types, ...$params);
$stmt->execute();
$products_result = $stmt->get_result();
$products = [];
while ($row = $products_result->fetch_assoc()) {
    $products[] = $row;
}
$stmt->close();

// Fetch categories for filter
$categories_sql = "SELECT category_id, category_name FROM categories WHERE is_active = 1 ORDER BY category_name";
$categories_result = $conn->query($categories_sql);
$categories = [];
while ($cat = $categories_result->fetch_assoc()) {
    $categories[] = $cat;
}
?>
<?php include './inc/header.php'; ?>
    
    <!-- Shop page content outside body-con wrapper -->
    <section class="shop-title-con">
        <h2 class="shop-title">Shop All Products</h2>
        <span class="sub-text">Browse our collection of fashion-forward clothing and accessories.</span>
    </section>
    <section class="filter-section">
        <button class="filter-btn"><i class='bx  bx-filter'></i>Filter Products</button>
        <div class="filter-overlay" id="filterOverlay"></div>
        <div class="filter-details" id="filterDetails">
            <div class="filter-head">
                <h4>Filter Options</h4>
                <i class='bx  bx-x' id="closeIcon"></i> 
            </div>
            <!-- this is category dropdown -->
            <div class="filter-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['category_id']; ?>" <?php echo ($category_filter == $cat['category_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- this is size dropdown -->
            <div class="filter-group">
                <label for="size">Size</label>
                <select id="size" name="size">
                    <option value="xs">XS</option>
                    <option value="s">S</option>
                    <option value="m">M</option>
                    <option value="l">L</option>
                    <option value="xl">XL</option>
                    <option value="xxl">XXL</option>
                </select>
            </div>
            <!-- this is color dropdown -->
            <div class="filter-group">
                <label for="color">Color</label>
                <select id="color" name="color">
                    <option value="red">Red</option>
                    <option value="blue">Blue</option>
                    <option value="green">Green</option>
                    <option value="black">Black</option>
                    <option value="white">White</option>
                    <option value="yellow">Yellow</option>
                </select>
            </div>
            <!-- this is price range dropdown -->
            <div class="filter-group">
                <label for="priceRange">Price Range</label>
                <input type="range" name="priceRange" id="priceRange" min="0" max="1000" step="10" value="<?php echo $price_max >= 10000 ? 1000 : $price_max; ?>">
                <span id="priceValue">$0 - $<?php echo $price_max >= 10000 ? 1000 : $price_max; ?></span>
            </div>
            <div class="filter-action-btn">
                <button class="apply-filter-btn">Apply Filters</button>
                <button class="clear-filter-btn">Clear Filters</button>
            </div>
        </div>
    </section>
    <section class="product-result-con">
        <span class="showing-result">Showing <?php echo count($products); ?> of <?php echo $total_products; ?> products</span>
        <div class="products">
            <!-- Products will be dynamically loaded here -->
            <?php if (empty($products)): ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">
                    <i class='bx bx-shopping-bag' style="font-size: 48px;"></i>
                    <p style="margin-top: 16px; font-size: 18px;">No products found</p>
                    <p style="margin-top: 8px;">Try adjusting your filters</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): 
                    // Handle image path correctly
                    $image_path = './images/hero-img.png'; // Default fallback
                    if (!empty($product['image_url'])) {
                        // If path starts with ../ (from admin), convert to ./
                        if (strpos($product['image_url'], '../images/') === 0) {
                            $image_path = str_replace('../images/', './images/', $product['image_url']);
                        } else {
                            $image_path = $product['image_url'];
                        }
                    }
                    
                    $rating = round($product['avg_rating'], 1);
                    $full_stars = floor($rating);
                    $has_half = ($rating - $full_stars) >= 0.5;
                    $empty_stars = 5 - $full_stars - ($has_half ? 1 : 0);
                    $display_price = $product['discount_percentage'] > 0 ? $product['price'] : $product['original_price'];
                ?>
                <div class="product-cart">
                    <div class="like" data-product-id="<?php echo $product['product_id']; ?>" style="cursor: pointer;">
                        <i class='bx bx-heart'></i> 
                    </div>
                    <a href="product.php?id=<?php echo $product['product_id']; ?>" class="product-link">
                        <picture>
                            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="trending-image" loading="lazy">
                        </picture>
                        <p class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></p>
                        <div class="stars">
                            <?php for ($i = 0; $i < $full_stars; $i++): ?>
                                <i class='bx bxs-star'></i>
                            <?php endfor; ?>
                            <?php if ($has_half): ?>
                                <i class='bx bxs-star-half'></i>
                            <?php endif; ?>
                            <?php for ($i = 0; $i < $empty_stars; $i++): ?>
                                <i class='bx bx-star'></i>
                            <?php endfor; ?>
                            <span>(<?php echo $rating; ?>)</span>
                        </div>
                        <span class="product-price">$<?php echo number_format($display_price, 2); ?></span>
                    </a>
                    <button class="addToCartBtn" data-product-id="<?php echo $product['product_id']; ?>"><i class='bx bxs-shopping-bag-alt'></i>Add to Cart</button>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php if ($total_pages > 1): ?>
    <section class="pagination-con">
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?><?php echo !empty($category_filter) ? '&category=' . $category_filter : ''; ?><?php echo $price_max < 10000 ? '&price=' . $price_max : ''; ?>" class="prev-page"><i class='bx bx-chevron-left'></i> Previous</a>
            <?php endif; ?>
            
            <?php 
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $page + 2);
            
            for ($i = $start_page; $i <= $end_page; $i++): 
            ?>
                <a href="?page=<?php echo $i; ?><?php echo !empty($category_filter) ? '&category=' . $category_filter : ''; ?><?php echo $price_max < 10000 ? '&price=' . $price_max : ''; ?>" class="page-number <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?><?php echo !empty($category_filter) ? '&category=' . $category_filter : ''; ?><?php echo $price_max < 10000 ? '&price=' . $price_max : ''; ?>" class="next-page">Next <i class='bx bx-chevron-right'></i></a>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
    </main>

    <script>
        // Filter panel functionality
        const filterBtn = document.querySelector('.filter-btn');
        const filterDetails = document.getElementById('filterDetails');
        const filterOverlay = document.getElementById('filterOverlay');
        const closeIcon = document.getElementById('closeIcon');
        const priceRange = document.getElementById('priceRange');
        const priceValue = document.getElementById('priceValue');

        // Open filter panel
        filterBtn.addEventListener('click', () => {
            filterDetails.classList.add('active');
            filterOverlay.classList.add('active');
        });

        // Close filter panel
        const closeFilter = () => {
            filterDetails.classList.remove('active');
            filterOverlay.classList.remove('active');
        };

        closeIcon.addEventListener('click', closeFilter);
        filterOverlay.addEventListener('click', closeFilter);

        // Price range slider
        priceRange.addEventListener('input', (e) => {
            const value = e.target.value;
            priceValue.textContent = `$0 - $${value}`;
            // Update slider background
            const percent = (value / priceRange.max) * 100;
            priceRange.style.background = `linear-gradient(to right, var(--primary-btn) 0%, var(--primary-btn) ${percent}%, var(--border-color) ${percent}%, var(--border-color) 100%)`;
        });

        // Clear filters
        document.querySelector('.clear-filter-btn').addEventListener('click', () => {
            window.location.href = 'shop.php';
        });

        // Apply filters
        document.querySelector('.apply-filter-btn').addEventListener('click', () => {
            const category = document.getElementById('category').value;
            const price = document.getElementById('priceRange').value;
            
            let url = 'shop.php?';
            const params = [];
            
            if (category) {
                params.push('category=' + category);
            }
            if (price < 1000) {
                params.push('price=' + price);
            }
            
            url += params.join('&');
            window.location.href = url;
        });

        // Add to Cart with SweetAlert2
        document.querySelectorAll('.addToCartBtn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const productId = this.getAttribute('data-product-id');
                
                // Send AJAX request to add to cart
                fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'product_id=' + productId + '&quantity=1'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true,
                            position: 'top-end'
                        });
                    } else if (data.login_required) {
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
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
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
                        text: 'Something went wrong. Please try again.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                });
            });
        });

        // Add to Wishlist functionality
        document.querySelectorAll('.like').forEach(likeBtn => {
            likeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = this.getAttribute('data-product-id');
                const icon = this.querySelector('i');
                
                // Send AJAX request to add to wishlist
                fetch('add_to_wishlist.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'product_id=' + productId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Toggle heart icon
                        icon.classList.toggle('bx-heart');
                        icon.classList.toggle('bxs-heart');
                        icon.style.color = icon.classList.contains('bxs-heart') ? '#ef4444' : '';
                        
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true,
                            position: 'top-end'
                        });
                    } else if (data.login_required) {
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
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
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
                        text: 'Something went wrong. Please try again.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                });
            });
        });
    </script>
<?php include './inc/footer.php'; ?>