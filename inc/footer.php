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
              <li><a href="index.php">Home</a></li>
              <li><a href="shop.php">Shop</a></li>
              <li><a href="about.php">About Us</a></li>
              <li><a href="contact.php">Contact</a></li>
            </ul>
          </div>
          
          <!-- Categories -->
          <div class="footer-column">
            <h4 class="footer-heading">Categories</h4>
            <ul class="footer-links">
              <li><a href="shop.php?category=women">Women's Wear</a></li>
              <li><a href="shop.php?category=men">Men's Wear</a></li>
              <li><a href="shop.php?category=formal">Formal Wear</a></li>
              <li><a href="shop.php?category=casual">Casual Wear</a></li>
              <li><a href="shop.php?category=accessories">Accessories</a></li>
              <li><a href="shop.php?category=new">New Arrivals</a></li>
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
