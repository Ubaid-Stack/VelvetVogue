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
                    <option value="women">Women's Wear</option>
                    <option value="men">Men's Wear</option>
                    <option value="formal-wear">Formal Wear</option>
                    <option value="casual-wear">Casual Wear</option>
                    <option value="accessories">Accessories</option>
                    <option value="new-arrivals">New Arrivals</option>
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
                <input type="range" name="priceRange" id="priceRange" min="0" max="500" step="10">
                <span id="priceValue">$0 - $500</span>
            </div>
            <div class="filter-action-btn">
                <button class="apply-filter-btn">Apply Filters</button>
                <button class="clear-filter-btn">Clear Filters</button>
            </div>
        </div>
    </section>
    <section class="product-result-con">
        <span class="showing-result">showing 12 products</span>
        <div class="products">
            <!-- Products will be dynamically loaded here -->
             <div class="product-cart">
                <!-- Product cards -->
                <div class="like">
                <i class='bx  bx-heart'></i> 
                </div>
                <picture>
                    <img src="./images/womensWear.webp" alt="Trending Product 2" class="trending-image" loading="lazy">
                    <button class="addToCartBtn"><i class='bx bxs-shopping-bag-alt'></i>Add to Cart</button>
                </picture>
                <p class="product-name">T-Shirt</p>
                <div class="stars">
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <span>(4.8)</span>
              </div>
              <span class="product-price">$19.99</span>
            </div>
             <div class="product-cart">
                <!-- Product cards -->
                <div class="like">
                <i class='bx  bx-heart'></i> 
                </div>
                <picture>
                    <img src="./images/womensWear.webp" alt="Trending Product 2" class="trending-image" loading="lazy">
                    <button class="addToCartBtn"><i class='bx bxs-shopping-bag-alt'></i>Add to Cart</button>
                </picture>
                <p class="product-name">T-Shirt</p>
                <div class="stars">
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <span>(4.8)</span>
              </div>
              <span class="product-price">$19.99</span>
            </div>
             <div class="product-cart">
                <!-- Product cards -->
                <div class="like">
                <i class='bx  bx-heart'></i> 
                </div>
                <picture>
                    <img src="./images/womensWear.webp" alt="Trending Product 2" class="trending-image" loading="lazy">
                    <button class="addToCartBtn"><i class='bx bxs-shopping-bag-alt'></i>Add to Cart</button>
                </picture>
                <p class="product-name">T-Shirt</p>
                <div class="stars">
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <span>(4.8)</span>
              </div>
              <span class="product-price">$19.99</span>
            </div>
             <div class="product-cart">
                <!-- Product cards -->
                <div class="like">
                <i class='bx  bx-heart'></i> 
                </div>
                <picture>
                    <img src="./images/womensWear.webp" alt="Trending Product 2" class="trending-image" loading="lazy">
                    <button class="addToCartBtn"><i class='bx bxs-shopping-bag-alt'></i>Add to Cart</button>
                </picture>
                <p class="product-name">T-Shirt</p>
                <div class="stars">
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <span>(4.8)</span>
              </div>
              <span class="product-price">$19.99</span>
            </div>
        </div>
    </section>
    <section class="pagination-con">
        <div class="pagination">
            <a href="#" class="prev-page"><i class='bx bx-chevron-left'></i> Previous</a>
            <a href="#" class="page-number active">1</a>
            <a href="#" class="page-number">2</a>
            <a href="#" class="page-number">3</a>
            <a href="#" class="next-page">Next <i class='bx bx-chevron-right'></i></a>
        </div>
    </section>
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
            document.getElementById('category').selectedIndex = 0;
            document.getElementById('size').selectedIndex = 0;
            document.getElementById('color').selectedIndex = 0;
            priceRange.value = 500;
            priceValue.textContent = '$0 - $500';
            priceRange.style.background = `linear-gradient(to right, var(--primary-btn) 0%, var(--primary-btn) 100%, var(--border-color) 100%, var(--border-color) 100%)`;
        });

        // Apply filters (placeholder - add your filtering logic)
        document.querySelector('.apply-filter-btn').addEventListener('click', () => {
            console.log('Filters applied');
            closeFilter();
        });
    </script>
<?php include './inc/footer.php'; ?>