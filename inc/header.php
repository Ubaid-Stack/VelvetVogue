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
    <link rel="stylesheet" href="./assets/home.css?v=20251119g">
    <link rel="stylesheet" href="./assets/animation.css?v=20251119s">
    <link rel="stylesheet" href="./assets/style.css?v=20251119g">
    <title>Home</title>
  </head>
  <body>
      <header class="header">
        <nav class="navbar">
            <div class="logo">
                <h4>Velvet Vogue</h4>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php" >Shop</a></li>
                <li><a href="#">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
             <div class="nav-actions">
              <div class="menu"><i class='bx bx-search' id="searchIcon"></i></div>
              <a href="wishlist.php"><div class="menu" id="wishlist"><i class='bx bx-heart'></i></div></a>
              <a href="cart.php"><div class="menu" id="myCart"><i class='bx bx-cart'></i></div></a>
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
            <li><a href="index.php"><i class='bx bx-home-alt'></i> Home</a></li>
            <li><a href="shop.php"><i class='bx bx-store'></i> Shop</a></li>
            <li><a href="#"><i class='bx bx-info-circle'></i> About</a></li>
            <li><a href="contact.php"><i class='bx bx-envelope'></i> Contact</a></li>
            <li class="mobile-divider"></li>
            <li><a href="profile.php"><i class='bx bx-user'></i> My Profile</a></li>
            <li><a href="wishlist.php"><i class='bx bx-heart'></i> My Wishlist</a></li>
            <li><a href="cart.php"><i class='bx bx-cart'></i> My Cart</a></li>
          </ul>
        </div>
      </header>
      <main class="body-con">