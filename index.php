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
  <link rel="stylesheet" href="style.css?v=20251019">
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
                <li><a href="#"><i class='bx bx-heart'></i></a></li>
                <li><a href="#"><i class='bx bx-cart'></i></a></li>
                <a href="#"><i class='bx bx-user'></i></a>
            </ul>
             <div class="nav-actions">
              <div class="menu"><i class='bx bx-search' id="searchIcon"></i></div>
              <div class="menu"><i class='bx bx-menu'></i></div>
            </div>
            <!-- this is for search bar -->
            <div class="search" id="searchCon">
                <i class='bx bx-search'></i>
                <input type="search" placeholder="Search">
            </div>
        </nav>
      </header>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
      // this is search bar visible script
      const searchCon = document.getElementById('searchCon');
      const searchIcon = document.getElementById('searchIcon');

      searchIcon.addEventListener('click', () => {
        if(searchIcon.classList.contains('bx-search')){
          searchCon.style.display = 'flex';
          searchIcon.classList.replace('bx-search', 'bx-x');
        } else {
          searchCon.style.display = 'none';
          searchIcon.classList.replace('bx-x', 'bx-search');
        } 
      });
    </script>
  </body>
</html>