<?php include './inc/header.php'; ?>
        <section class="hero-section">
          <div class="hero-content">
            <h1 class="main-text">Unleash Your Style with Velvet Vogue</h1>
            <p class="sub-text">Trendy, youthful, and bold — explore outfits that make every moment shine.</p>
            <div class="button-group">
              <a href="#" class="shop-btn">Shop Now</a>
              <a href="#" class="cat-btn">Explore Categories <i class='bx bx-chevron-right'></i></a>
            </div>
          </div>
          <figure class="hero-image-container">
            <img src="./images/heroImg.png" alt="Hero Image" class="hero-image" loading="eager">
          </figure>
        </section>
        <section class="Categories-section">
          <h2 class="section-title">Shop by Category</h2>
          <div class="categories-container ">
            <div class="category-card">
              <img src="./images/Best-British-Menswear-Brands.webp" alt="Men's Wear" class="category-image" loading="lazy">
              <div class="navBtn">
                <h3 class="category-title">Men's Wear</h3>
                <button class="exploreBtn">Explore <i class='bx bx-chevron-right'></i></button>
              </div>
            </div>
            <div class="category-card">
              <img src="./images/womensWear.webp" alt="Women's Wear" class="category-image" loading="lazy">
              <div class="navBtn">
                <h3 class="category-title">Women's Wear</h3>
                <button class="exploreBtn">Explore <i class='bx bx-chevron-right'></i></button>
              </div>
            </div>
            <div class="category-card">
              <img src="./images/formal-attire-top-2-604x270.webp" alt="Formal Wear" class="category-image" loading="lazy">
              <div class="navBtn">
                <h3 class="category-title">Formal Wear</h3>
                <button class="exploreBtn">Explore <i class='bx bx-chevron-right'></i></button>
              </div>
            </div>
            <div class="category-card">
              <img src="./images/casual.webp" alt="Casual Wear" class="category-image" loading="lazy">
              <div class="navBtn">
                <h3 class="category-title">Casual Wear</h3>
                <button class="exploreBtn">Explore <i class='bx bx-chevron-right'></i></button>
              </div>
            </div>
            <div class="category-card">
              <img src="./images/accs.webp" alt="Accessories" class="category-image" loading="lazy">
              <div class="navBtn">
                <h3 class="category-title">Accessories</h3>
                <button class="exploreBtn">Explore <i class='bx bx-chevron-right'></i></button>
              </div>
            </div>
            <div class="category-card">
              <img src="./images/newArr.webp" alt="New Arrivals" class="category-image" loading="lazy">
              <div class="navBtn">
                <h3 class="category-title">New Arrivals</h3>
                <button class="exploreBtn">Explore <i class='bx bx-chevron-right'></i></button>
              </div>
            </div>
          </div>
        </section>
        <section class="trending-product">
          <h2 class="section-title">Trending Products</h2>
          <div class="trending-container">
            <?php
            require_once './inc/db.php';
            
            // Fetch trending/featured products (limited to 8)
            $query = "SELECT p.product_id, p.product_name, p.price, p.original_price, p.discount_percentage, 
                             pi.image_url, 
                             COALESCE(AVG(r.rating), 0) as avg_rating,
                             COUNT(r.review_id) as review_count
                      FROM products p
                      LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = TRUE
                      LEFT JOIN reviews r ON p.product_id = r.product_id
                      WHERE p.status = 'active' AND p.is_featured = 1
                      GROUP BY p.product_id
                      ORDER BY p.created_at DESC
                      LIMIT 8";
            
            $result = $conn->query($query);
            
            if ($result && $result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) {
                    $productId = $product['product_id'];
                    $productName = htmlspecialchars($product['product_name']);
                    $price = number_format($product['price'], 2);
                    $originalPrice = $product['original_price'] ? number_format($product['original_price'], 2) : null;
                    $discount = $product['discount_percentage'];
                    $avgRating = round($product['avg_rating'], 1);
                    $reviewCount = $product['review_count'];
                    
                    // Handle image URL
                    $imageUrl = $product['image_url'] ?? './images/placeholder.jpg';
                    if (strpos($imageUrl, '../images/') === 0) {
                        $imageUrl = str_replace('../images/', './images/', $imageUrl);
                    }
                    
                    // Generate star rating
                    $fullStars = floor($avgRating);
                    $halfStar = ($avgRating - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
            ?>
            <div class="trending-cart" data-product-id="<?php echo $productId; ?>">
              <div class="like" onclick="toggleWishlist(<?php echo $productId; ?>, this)">
                <i class='bx bx-heart'></i> 
              </div>
              <?php if ($discount > 0): ?>
              <span class="discount-badge">-<?php echo $discount; ?>%</span>
              <?php endif; ?>
              <picture>
                <img src="<?php echo $imageUrl; ?>" alt="<?php echo $productName; ?>" class="trending-image" loading="lazy">
                <button class="addToCartBtn" onclick="addToCart(<?php echo $productId; ?>)">
                  <i class='bx bxs-shopping-bag-alt'></i>Add to Cart
                </button>
              </picture>
              <a href="product.php?id=<?php echo $productId; ?>" class="product-name"><?php echo $productName; ?></a>
              <div class="stars">
                <?php 
                for ($i = 0; $i < $fullStars; $i++) {
                    echo "<i class='bx bxs-star'></i>";
                }
                if ($halfStar) {
                    echo "<i class='bx bxs-star-half'></i>";
                }
                for ($i = 0; $i < $emptyStars; $i++) {
                    echo "<i class='bx bx-star'></i>";
                }
                ?>
                <span>(<?php echo $avgRating > 0 ? $avgRating : 'New'; ?>)</span>
              </div>
              <div class="price-section">
                <span class="product-price">$<?php echo $price; ?></span>
                <?php if ($originalPrice): ?>
                <span class="original-price">$<?php echo $originalPrice; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <?php 
                }
            } else {
                echo '<p style="grid-column: 1/-1; text-align: center; padding: 2rem;">No trending products available at the moment.</p>';
            }
            ?>
          </div>
          <a href="shop.php" class="allProductsBtn">View All Products</a>
        </section>
        <section class="state">
          <div class="allState">
              <h1>Trusted by Fashion Lovers Everywhere</h1>
              <div>
                <i class='bx  bxs-happy'></i> 
                <h1><span class="count" data-target="5" data-suffix="K+">0</span></h1>
                <p>Happy Customers</p>  
              </div>
              <div>
                <i class='bx bxs-shopping-bag'></i> 
                <h1><span class="count" data-target="300" data-suffix="+">0</span></h1>
                <p>Daily Orders</p>
              </div>
              <div>
                <i class='bx  bxs-user'></i> 
                <h1><span class="count" data-target="95" data-suffix="%">0</span></h1>
                <p>Customer Satisfaction</p>
              </div>
              <div>
                <i class='bx  bxs-star'></i> 
                <h1><span class="count" data-target="4.8" data-suffix="">0</span></h1>
                <p>Average Customer Rating</p>
              </div>
          </div>
        </section>
        <!-- this is feedback section  -->
        <section class="feedback-container">
          <h1 class="feedback-head">Velvet Vogue Customer Says</h1>
          <div class="carousel">
            
            <div class="card">
              <div class="rating-stars">
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
              </div>
              <span class="feedback">
                Such an amazing shopping experience! The product descriptions were accurate, and the fabric quality is excellent. I've already received so many compliments on my new outfits. Will definitely shop again!
              </span>
              <h3 class="customer-name">Mohamed Akram</h3>
              <span class="customer-status">Verified Buyer</span>
            </div>
            <div class="card">
              <div class="rating-stars">
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
              </div>
              <span class="feedback">
                Great service and perfect clothing! The prices are fair, the styles are modern, and everything arrived neatly packed. I'm very impressed with the overall experience. This store stands out from the rest.
              </span>
              <h3 class="customer-name">Musni Ahamed</h3>
              <span class="customer-status">Returning Customer</span>
            </div>
            <div class="card">
              <div class="rating-stars">
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
              </div>
              <span class="feedback">
                My order arrived earlier than expected, and the clothes were exactly as shown on the website. The material feels premium and very comfortable. This is one of the best online clothing stores I've purchased from.
              </span>
              <h3 class="customer-name">Mohamed Munsif</h3>
              <span class="customer-status">Happy Shopper</span>
            </div>
  
          </div>
        </section>
        <!-- this is faq section -->
        <section class="faq-section">
          <h1 class="faq-heading">Frequently Asked Questions</h1>
          <div class="faq-container">
            <details class="faq-item">
              <summary class="faq-question"><h3>What payment methods does Velvet Vogue accept?</h3></summary>
              <p class="faq-answer">Velvet Vogue accepts credit/debit cards, mobile payments, bank transfers, and cash on delivery (for selected locations).</p>
            </details>
            <details class="faq-item">
              <summary class="faq-question"><h3>How long does delivery take?</h3></summary>
              <p class="faq-answer">Our standard delivery time is 2–5 business days depending on your location.Once shipped, you will receive a tracking link for your order.</p>
            </details>
            <details class="faq-item">
              <summary class="faq-question"><h3>Can I return or exchange an item?</h3></summary>
              <p class="faq-answer">Yes! Velvet Vogue offers 7–14 day returns and exchanges for unused, unwashed items in their original packaging. Contact our support team to begin the process.</p>
            </details>
            <details class="faq-item">
              <summary class="faq-question"><h3>What should I do if I receive a damaged or incorrect item?</h3></summary>
              <p class="faq-answer">We apologize for the inconvenience!
                Please send a photo of the issue within 48 hours, and we will send a replacement or issue a full refund.</p>
            </details>
            <details class="faq-item">
              <summary class="faq-question"><h3>Are Velvet Vogue products high quality?</h3></summary>
              <p class="faq-answer">Absolutely. All Velvet Vogue items go through multiple quality checks to ensure premium fabric, stitching, and finishing.</p>
            </details>
          </div>
        </section>
        <!-- this is newsletter section -->
        <section class="newsletter-section">
          <div class="newsletter-container">
            <div class="newsletter-icon">
              <i class='bx bx-envelope'></i>
            </div>
            <h2 class="newsletter-title">Subscribe to Our Newsletter</h2>
            <p class="newsletter-text">Join our community to receive exclusive offers, style tips, and be the first to know about new arrivals.</p>
            <form class="newsletter-form">
              <input type="email" placeholder="Your email address" class="newsletter-input" required>
              <button type="submit" class="newsletter-btn">Join Now</button>
            </form>
            <p class="newsletter-disclaimer">By subscribing, you agree to our Privacy Policy and consent to receive updates from our company.</p>
          </div>
          <div class="glass-bubbles">
            <!-- 20 bubbles -->
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
          </div>
        </section>
      </main>
      
    <script>
      // Add to Cart function
      function addToCart(productId) {
        <?php if (isset($_SESSION['user_id'])): ?>
          fetch('add_to_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}&quantity=1`
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Added to Cart!',
                text: data.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
              });
            } else if (data.login_required) {
              window.location.href = 'login.php';
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
          .catch(error => console.error('Error:', error));
        <?php else: ?>
          Swal.fire({
            icon: 'warning',
            title: 'Please Login',
            text: 'You need to login to add items to cart',
            showCancelButton: true,
            confirmButtonText: 'Login',
            cancelButtonText: 'Cancel'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = 'login.php';
            }
          });
        <?php endif; ?>
      }

      // Toggle Wishlist function
      function toggleWishlist(productId, element) {
        <?php if (isset($_SESSION['user_id'])): ?>
          fetch('add_to_wishlist.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}`
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              const icon = element.querySelector('i');
              if (data.action === 'added') {
                icon.classList.remove('bx-heart');
                icon.classList.add('bxs-heart');
                Swal.fire({
                  icon: 'success',
                  title: 'Added to Wishlist',
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 1500
                });
              } else {
                icon.classList.remove('bxs-heart');
                icon.classList.add('bx-heart');
                Swal.fire({
                  icon: 'info',
                  title: 'Removed from Wishlist',
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 1500
                });
              }
            }
          })
          .catch(error => console.error('Error:', error));
        <?php else: ?>
          Swal.fire({
            icon: 'warning',
            title: 'Please Login',
            text: 'You need to login to add items to wishlist',
            showCancelButton: true,
            confirmButtonText: 'Login',
            cancelButtonText: 'Cancel'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = 'login.php';
            }
          });
        <?php endif; ?>
      }

      // this is for count increment code
      const counters = document.querySelectorAll(".count")
      counters.forEach(count => {
        const target = parseFloat(count.getAttribute('data-target'));
        const suffix = count.getAttribute('data-suffix') || '';
        const isDecimal = (target % 1) !== 0;
        const step = target / 200;
        let current = 0;
        
        const updateCount = () => {
          current += step;
          
          if(current < target){
            if(isDecimal){
              count.textContent = current.toFixed(1) + suffix;
            } else {
              count.textContent = Math.ceil(current) + suffix;
            }
            requestAnimationFrame(updateCount);
          } else {
            if(isDecimal){
              count.textContent = target.toFixed(1) + suffix;
            } else {
              count.textContent = target + suffix;
            }
          }
        }
        updateCount();
      })
    </script>
<?php include './inc/footer.php'; ?>
    