<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preload" href="heroImg.png" as="image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/home.css?v=20251124h">
    <link rel="stylesheet" href="./assets/animation.css?v=20251119s">
    <link rel="stylesheet" href="./assets/style.css?v=20251124L">
    <link rel="stylesheet" href="./assets/product.css?v=20251121">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Home</title>
  </head>
  <body>
      <?php
      // Start session if not already started
      if (session_status() === PHP_SESSION_NONE) {
          session_start();
      }
      
      // Get current page name
      $currentPage = basename($_SERVER['PHP_SELF']);
      
      // Check if user is logged in
      $isLoggedIn = isset($_SESSION['user_id']);
      $userType = $_SESSION['user_type'] ?? 'customer';
      
      // Get user info if logged in
      if ($isLoggedIn && !isset($headerUserName)) {
          $headerUserId = $_SESSION['user_id'];
          if (isset($conn)) {
              $headerUserQuery = "SELECT full_name, username, profile_image FROM users WHERE user_id = ?";
              $headerUserStmt = $conn->prepare($headerUserQuery);
              $headerUserStmt->bind_param("i", $headerUserId);
              $headerUserStmt->execute();
              $headerUserResult = $headerUserStmt->get_result();
              if ($headerUserData = $headerUserResult->fetch_assoc()) {
                  $headerUserName = $headerUserData['full_name'] ?? $headerUserData['username'];
                  $headerUserImage = $headerUserData['profile_image'];
              }
              $headerUserStmt->close();
          }
      }
      ?>
      <header class="header">
        <nav class="navbar">
            <div class="logo">
                <h4>Velvet Vogue</h4>
            </div>
            <ul class="nav-links">
                <li><a href="index.php" class="<?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>">Home</a></li>
                <li><a href="shop.php" class="<?php echo ($currentPage == 'shop.php') ? 'active' : ''; ?>">Shop</a></li>
                <li><a href="about.php" class="<?php echo ($currentPage == 'about.php') ? 'active' : ''; ?>">About</a></li>
                <li><a href="contact.php" class="<?php echo ($currentPage == 'contact.php') ? 'active' : ''; ?>">Contact</a></li>
            </ul>
             <div class="nav-actions">
              <div class="menu"><i class='bx bx-search' id="searchIcon"></i></div>
              <a href="wishlist.php"><div class="menu" id="wishlist"><i class='bx bx-heart'></i></div></a>
              <a href="cart.php"><div class="menu" id="myCart"><i class='bx bx-cart'></i></div></a>
              
              <?php if ($isLoggedIn): ?>
                  <!-- Logged in user - redirect to profile -->
                  <a href="profile.php"><div class="menu" id="profile"><i class='bx bx-user'></i></div></a>
              <?php else: ?>
                  <!-- Guest user - redirect to login -->
                  <a href="login.php"><div class="menu" id="profile"><i class='bx bx-user'></i></div></a>
              <?php endif; ?>
              
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
            <li><a href="index.php" class="<?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>"><i class='bx bx-home-alt'></i> Home</a></li>
            <li><a href="shop.php" class="<?php echo ($currentPage == 'shop.php') ? 'active' : ''; ?>"><i class='bx bx-store'></i> Shop</a></li>
            <li><a href="about.php" class="<?php echo ($currentPage == 'about.php') ? 'active' : ''; ?>"><i class='bx bx-info-circle'></i> About</a></li>
            <li><a href="contact.php" class="<?php echo ($currentPage == 'contact.php') ? 'active' : ''; ?>"><i class='bx bx-envelope'></i> Contact</a></li>
            <li class="mobile-divider"></li>
            <?php if ($isLoggedIn): ?>
                <?php if ($userType === 'admin'): ?>
                    <li><a href="admin/dashboard.php"><i class='bx bx-grid-alt'></i> Admin Dashboard</a></li>
                <?php endif; ?>
                <li><a href="profile.php"><i class='bx bx-user'></i> My Profile</a></li>
                <li><a href="order.php"><i class='bx bx-package'></i> My Orders</a></li>
                <li><a href="wishlist.php"><i class='bx bx-heart'></i> My Wishlist</a></li>
                <li><a href="cart.php"><i class='bx bx-cart'></i> My Cart</a></li>
                <li><a href="accountSetting.php"><i class='bx bx-cog'></i> Settings</a></li>
                <li class="mobile-divider"></li>
                <li><a href="logout.php" class="logout-mobile"><i class='bx bx-log-out'></i> Logout</a></li>
            <?php else: ?>
                <li><a href="login.php"><i class='bx bx-user'></i> Login</a></li>
                <li><a href="register.php"><i class='bx bx-user-plus'></i> Register</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </header>
      <main class="body-con">