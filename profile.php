<?php include 'inc/header.php'; ?>

<section class="profile-con">
    <!-- Mobile Menu Toggle Button -->
    <button class="profile-menu-toggle" id="profileMenuToggle">
        <i class='bx bx-menu'></i>
        <span>Menu</span>
    </button>

    <!-- Overlay for mobile sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="profile-layout">
        <?php include 'inc/sidebar.php'; ?>
        <!-- Main Content -->
        <main class="profile-main">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-avatar">
                    <img src="./images/womensWear.webp" alt="Sophia Martinez" onerror="this.src='./images/product1.jpg'">
                </div>
                <div class="profile-info">
                    <h1 class="profile-name">Sophia Martinez</h1>
                    <p class="profile-email">sophia.martinez@example.com</p>
                    <p class="profile-phone">+1 (555) 123-4567</p>
                </div>
                <button class="edit-profile-btn">
                    <i class='bx bx-edit'></i>
                    <span>Edit Profile</span>
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="profile-stats">
                <div class="stat-card">
                    <div class="stat-icon orders">
                        <i class='bx bx-shopping-bag'></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">3</h3>
                        <p class="stat-label">Total Orders</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon wishlist">
                        <i class='bx bx-heart'></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">4</h3>
                        <p class="stat-label">Wishlist Items</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon pending">
                        <i class='bx bx-truck'></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">2</h3>
                        <p class="stat-label">Pending Deliveries</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon completed">
                        <i class='bx bx-check-circle'></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">1</h3>
                        <p class="stat-label">Completed Deliveries</p>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">Recent Orders</h2>
                    <a href="#" class="view-all-link">View All</a>
                </div>

                <div class="orders-list">
                    <div class="order-item">
                        <div class="order-details">
                            <h3 class="order-id">Order #VV-1234</h3>
                            <p class="order-date">Placed on Nov 15, 2025</p>
                        </div>
                        <div class="order-status">
                            <span class="status-badge delivered">Delivered</span>
                        </div>
                        <div class="order-amount">
                            <span class="amount">$129.99</span>
                        </div>
                        <button class="order-action-btn">View Details</button>
                    </div>

                    <div class="order-item">
                        <div class="order-details">
                            <h3 class="order-id">Order #VV-1233</h3>
                            <p class="order-date">Placed on Nov 10, 2025</p>
                        </div>
                        <div class="order-status">
                            <span class="status-badge shipping">Shipping</span>
                        </div>
                        <div class="order-amount">
                            <span class="amount">$89.99</span>
                        </div>
                        <button class="order-action-btn">Track Order</button>
                    </div>

                    <div class="order-item">
                        <div class="order-details">
                            <h3 class="order-id">Order #VV-1232</h3>
                            <p class="order-date">Placed on Nov 5, 2025</p>
                        </div>
                        <div class="order-status">
                            <span class="status-badge processing">Processing</span>
                        </div>
                        <div class="order-amount">
                            <span class="amount">$199.99</span>
                        </div>
                        <button class="order-action-btn">View Details</button>
                    </div>
                </div>
            </div>

            <!-- Saved Addresses Section -->
            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">Saved Addresses</h2>
                    <button class="add-new-btn">
                        <i class='bx bx-plus'></i>
                        <span>Add New</span>
                    </button>
                </div>

                <div class="addresses-grid">
                    <div class="address-card default">
                        <div class="address-badge">Default</div>
                        <h3 class="address-title">Home</h3>
                        <p class="address-text">
                            123 Main Street, Apt 4B<br>
                            New York, NY 10001<br>
                            United States
                        </p>
                        <div class="address-actions">
                            <button class="address-btn edit">
                                <i class='bx bx-edit'></i>
                                <span>Edit</span>
                            </button>
                            <button class="address-btn delete">
                                <i class='bx bx-trash'></i>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>

                    <div class="address-card">
                        <h3 class="address-title">Work</h3>
                        <p class="address-text">
                            456 Business Ave, Suite 200<br>
                            New York, NY 10022<br>
                            United States
                        </p>
                        <div class="address-actions">
                            <button class="address-btn edit">
                                <i class='bx bx-edit'></i>
                                <span>Edit</span>
                            </button>
                            <button class="address-btn delete">
                                <i class='bx bx-trash'></i>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</section>
</main>
<script>
    // Profile Sidebar Toggle for Mobile/Tablet
    const menuToggle = document.getElementById('profileMenuToggle');
    const sidebar = document.querySelector('.profile-sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (menuToggle && sidebar && overlay) {
        // Open sidebar
        menuToggle.addEventListener('click', function() {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        // Close sidebar when clicking overlay
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        // Close sidebar when clicking a nav item
        const navItems = sidebar.querySelectorAll('.profile-nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });
    }
</script>

<?php include 'inc/footer.php'; ?>