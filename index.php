<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css?v=20251115">
    <title>Home</title>
  </head>
  <body>
      <header class="header">
        <nav class="navbar">
            <div class="logo">
                <h4>Velvet Vogue</h4>
            </div>
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Shop</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
             <div class="nav-actions">
              <div class="menu"><i class='bx bx-search' id="searchIcon"></i></div>
              <div class="menu" id="wishlist"><i class='bx bx-heart'></i></div>
              <div class="menu" id="myCart"><i class='bx bx-cart'></i></div>
              <div class="menu" id="profile"><i class='bx bx-user'></i></div>
              <div class="menu" id="menuToggle"><i class='bx bx-menu'></i></div>
            </div>
            <!-- this is for search bar -->
            <div class="search" id="searchCon">
                <i class='bx bx-search'></i>
                <input type="search" placeholder="Search">
            </div>
        </nav>
        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
          <ul class="mobile-nav-links">
            <li><a href="#"><i class='bx bx-home-alt'></i> Home</a></li>
            <li><a href="#"><i class='bx bx-store'></i> Shop</a></li>
            <li><a href="#"><i class='bx bx-info-circle'></i> About</a></li>
            <li><a href="#"><i class='bx bx-envelope'></i> Contact</a></li>
            <li><a href="#"><i class='bx bx-help-circle'></i> FAQ</a></li>
            <li><a href="#"><i class='bx bx-refresh'></i> Returns & Exchanges</a></li>
            <li class="mobile-divider"></li>
            <li><a href="#"><i class='bx bx-user'></i> My Profile</a></li>
            <li><a href="#"><i class='bx bx-heart'></i> My Wishlist</a></li>
            <li><a href="#"><i class='bx bx-cart'></i> My Cart</a></li>
          </ul>
        </div>
      </header>
      <main class="body-con">
        <section class="hero-section">
          <div class="hero-content">
            <h1 class="main-text">Unleash Your Style with Velvet Vogue</h1>
            <p class="sub-text">Trendy, youthful, and bold — explore outfits that make every moment shine.</p>
            <a href="#" class="shop-btn">Shop Now</a>
            <a href="#" class="cat-btn">Explore Categories <i class='bx bx-chevron-right'></i></a>
          </div>
          <figure class="hero-image-container">
            <img src="heroImg.png" alt="Hero Image" class="hero-image">
          </figure>
        </section>
        <section class="Categories-section">
          <h2 class="section-title">Shop by Category</h2>
          <div class="categories-container ">
            <div class="category-card">
              <img src="./images/mensWear.webp" alt="Men's Wear" class="category-image">
              <div class="navBtn">
                <h3 class="category-title">Men's Wear</h3>
                <button class="exploreBtn">Explore <i class='bx bx-chevron-right'></i></button>
              </div>
            </div>
            <div class="category-card">
              <img src="./images/womensWear.webp" alt="Women's Wear" class="category-image">
              <div class="navBtn">
                <h3 class="category-title">Women's Wear</h3>
                <button class="exploreBtn">Explore <i class='bx bx-chevron-right'></i></button>
              </div>
            </div>
            <div class="category-card">
              <img src="./images/formal.jpg" alt="Formal Wear" class="category-image">
              <div class="navBtn">
                <h3 class="category-title">Formal Wear</h3>
                <button class="exploreBtn">Explore <i class='bx bx-chevron-right'></i></button>
              </div>
            </div>
            <div class="category-card">
              <img src="./images/casual.webp" alt="Casual Wear" class="category-image">
              <div class="navBtn">
                <h3 class="category-title">Casual Wear</h3>
                <button class="exploreBtn">Explore <i class='bx bx-chevron-right'></i></button>
              </div>
            </div>
            <div class="category-card">
              <img src="./images/accs.webp" alt="Accessories" class="category-image">
              <div class="navBtn">
                <h3 class="category-title">Accessories</h3>
                <button class="exploreBtn">Explore <i class='bx bx-chevron-right'></i></button>
              </div>
            </div>
            <div class="category-card">
              <img src="./images/newArr.webp" alt="New Arrivals" class="category-image">
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
            <div class="trending-cart">
            <!-- Product cards will go here -->
             <div class="like">
              <i class='bx  bx-heart'></i> 
             </div>
              <picture>
                <img src="./images/womensWear.webp" alt="Trending Product 1" class="trending-image">
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
            <div class="trending-cart">
              <!-- Product cards -->
               <div class="like">
              <i class='bx  bx-heart'></i> 
             </div>
              <picture>
                <img src="./images/womensWear.webp" alt="Trending Product 1" class="trending-image">
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
            <div class="trending-cart">
              <!-- Product cards -->
               <div class="like">
              <i class='bx  bx-heart'></i> 
             </div>
              <picture>
                <img src="./images/womensWear.webp" alt="Trending Product 1" class="trending-image">
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
          <button class="allProductsBtn">View All Products</button>
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
        </section>
      </main>
      <footer class="footer">
        <div class="footer-container">
          <!-- Brand Section -->
          <div class="footer-column footer-brand">
            <h3 class="footer-logo">Velvet Vogue</h3>
            <p class="footer-description">Where elegance meets everyday. Discover the perfect blend of style and comfort.</p>
            <div class="footer-social">
              <a href="#" class="social-link"><i class='bx bxl-instagram'></i></a>
              <a href="#" class="social-link"><i class='bx bxl-facebook'></i></a>
              <a href="#" class="social-link"><i class='bx bxl-twitter'></i></a>
            </div>
          </div>
          
          <!-- Quick Links -->
          <div class="footer-column">
            <h4 class="footer-heading">Quick Links</h4>
            <ul class="footer-links">
              <li><a href="#">Shop</a></li>
              <li><a href="#">About Us</a></li>
              <li><a href="#">Contact</a></li>
              <li><a href="#">FAQ</a></li>
              <li><a href="#">Returns & Exchanges</a></li>
            </ul>
          </div>
          
          <!-- Categories -->
          <div class="footer-column">
            <h4 class="footer-heading">Categories</h4>
            <ul class="footer-links">
              <li><a href="#">Women</a></li>
              <li><a href="#">Men</a></li>
              <li><a href="#">Accessories</a></li>
              <li><a href="#">New Arrivals</a></li>
              <li><a href="#">Sale</a></li>
            </ul>
          </div>
          
          <!-- Newsletter -->
          <div class="footer-column">
            <h4 class="footer-heading">Subscribe to Our Newsletter</h4>
            <p class="footer-newsletter-text">Get the latest updates on new products and upcoming sales.</p>
            <form class="footer-newsletter-form">
              <div class="footer-input-wrapper">
                <input type="email" placeholder="Your email address" class="footer-newsletter-input" required>
                <button type="submit" class="footer-newsletter-btn"><i class='bx bx-send'></i></button>
              </div>
            </form>
          </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
          <p class="footer-copyright">© 2025 Velvet Vogue. All rights reserved.</p>
          <div class="footer-legal">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
          </div>
        </div>
      </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
      // Search bar toggle script
      const searchCon = document.getElementById('searchCon');
      const searchIcon = document.getElementById('searchIcon');

      searchIcon.addEventListener('click', () => {
        searchCon.classList.toggle('active');
        if(searchIcon.classList.contains('bx-search')){
          searchIcon.classList.replace('bx-search', 'bx-x');
        }else{
          searchIcon.classList.replace('bx-x', 'bx-search');
        }
      });

      // Mobile menu toggle script
      const menuToggle = document.getElementById('menuToggle');
      const mobileMenu = document.getElementById('mobileMenu');

      menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
        const menuIcon = menuToggle.querySelector('i');
        if(mobileMenu.classList.contains('active')){
          menuIcon.classList.replace('bx-menu', 'bx-x');
        } else {
          menuIcon.classList.replace('bx-x', 'bx-menu');
        }
      });

      // Close mobile menu when clicking outside
      document.addEventListener('click', (e) => {
        if(!menuToggle.contains(e.target) && !mobileMenu.contains(e.target)){
          mobileMenu.classList.remove('active');
          const menuIcon = menuToggle.querySelector('i');
          if(menuIcon.classList.contains('bx-x')){
            menuIcon.classList.replace('bx-x', 'bx-menu');
          }
        }
      });
    </script>
  </body>
</html>
