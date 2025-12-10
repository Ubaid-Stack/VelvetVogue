<?php   
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) 
    || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once 'inc/db.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
$userQuery = "SELECT full_name, username, email, phone, profile_image FROM users WHERE user_id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
$userStmt->close();

// Fetch total orders count
$ordersQuery = "SELECT COUNT(*) as total_orders FROM orders WHERE user_id = ?";
$ordersStmt = $conn->prepare($ordersQuery);
$ordersStmt->bind_param("i", $user_id);
$ordersStmt->execute();
$ordersResult = $ordersStmt->get_result();
$ordersData = $ordersResult->fetch_assoc();
$total_orders = $ordersData['total_orders'];
$ordersStmt->close();

// Fetch wishlist count
$wishlistQuery = "SELECT COUNT(*) as total_wishlist FROM wishlist WHERE user_id = ?";
$wishlistStmt = $conn->prepare($wishlistQuery);
$wishlistStmt->bind_param("i", $user_id);
$wishlistStmt->execute();
$wishlistResult = $wishlistStmt->get_result();
$wishlistData = $wishlistResult->fetch_assoc();
$total_wishlist = $wishlistData['total_wishlist'];
$wishlistStmt->close();

// Fetch pending deliveries count
$pendingQuery = "SELECT COUNT(*) as pending_orders FROM orders WHERE user_id = ? AND order_status IN ('processing', 'shipped')";
$pendingStmt = $conn->prepare($pendingQuery);
$pendingStmt->bind_param("i", $user_id);
$pendingStmt->execute();
$pendingResult = $pendingStmt->get_result();
$pendingData = $pendingResult->fetch_assoc();
$pending_orders = $pendingData['pending_orders'];
$pendingStmt->close();

// Fetch completed deliveries count
$completedQuery = "SELECT COUNT(*) as completed_orders FROM orders WHERE user_id = ? AND order_status = 'delivered'";
$completedStmt = $conn->prepare($completedQuery);
$completedStmt->bind_param("i", $user_id);
$completedStmt->execute();
$completedResult = $completedStmt->get_result();
$completedData = $completedResult->fetch_assoc();
$completed_orders = $completedData['completed_orders'];
$completedStmt->close();

// Fetch recent orders (last 3)
$recentOrdersQuery = "SELECT order_id, order_date, order_status, total_amount 
                      FROM orders 
                      WHERE user_id = ? 
                      ORDER BY order_date DESC 
                      LIMIT 3";
$recentOrdersStmt = $conn->prepare($recentOrdersQuery);
$recentOrdersStmt->bind_param("i", $user_id);
$recentOrdersStmt->execute();
$recentOrdersResult = $recentOrdersStmt->get_result();
$recent_orders = [];
while ($order = $recentOrdersResult->fetch_assoc()) {
    $recent_orders[] = $order;
}
$recentOrdersStmt->close();

// Fetch saved addresses
$addressQuery = "SELECT address_id, address_type, full_name, phone, address_line1, address_line2, city, state, zip_code, country, is_default 
                 FROM addresses 
                 WHERE user_id = ? 
                 ORDER BY is_default DESC, address_id DESC";
$addressStmt = $conn->prepare($addressQuery);
$addressStmt->bind_param("i", $user_id);
$addressStmt->execute();
$addressResult = $addressStmt->get_result();
$addresses = [];
while ($address = $addressResult->fetch_assoc()) {
    $addresses[] = $address;
}
$addressStmt->close();

// Default profile image if not set
$profile_image = !empty($user['profile_image']) ? $user['profile_image'] : './images/default-avatar.png';
$display_name = !empty($user['full_name']) ? $user['full_name'] : $user['username'];
?>
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
                    <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="<?php echo htmlspecialchars($display_name); ?>" onerror="this.src='./images/product1.jpg'">
                </div>
                <div class="profile-info">
                    <h1 class="profile-name"><?php echo htmlspecialchars($display_name); ?></h1>
                    <p class="profile-email"><?php echo htmlspecialchars($user['email'] ?? 'No email provided'); ?></p>
                    <p class="profile-phone"><?php echo htmlspecialchars($user['phone'] ?? 'No phone provided'); ?></p>
                </div>
                <a href="editProfile.php" class="edit-profile-btn" style="text-decoration: none;">
                    <i class='bx bx-edit'></i>
                    <span>Edit Profile</span>
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="profile-stats">
                <div class="stat-card">
                    <div class="stat-icon orders">
                        <i class='bx bx-shopping-bag'></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number"><?php echo $total_orders; ?></h3>
                        <p class="stat-label">Total Orders</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon wishlist">
                        <i class='bx bx-heart'></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number"><?php echo $total_wishlist; ?></h3>
                        <p class="stat-label">Wishlist Items</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon pending">
                        <i class='bx bx-package'></i> 
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number"><?php echo $pending_orders; ?></h3>
                        <p class="stat-label">Pending Deliveries</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon completed">
                        <i class='bx bx-check-circle'></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number"><?php echo $completed_orders; ?></h3>
                        <p class="stat-label">Completed Deliveries</p>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">Recent Orders</h2>
                    <a href="order.php" class="view-all-link" style="text-decoration: none;">View All</a>
                </div>

                <div class="orders-list">
                    <?php if (empty($recent_orders)): ?>
                        <div class="empty-state">
                            <i class='bx bx-package'></i>
                            <p>No orders yet. Start shopping to see your orders here!</p>
                            <a href="shop.php" class="btn-primary">Browse Products</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recent_orders as $order): 
                            // Format order date
                            $order_date = date('M d, Y', strtotime($order['order_date']));
                            
                            // Status badge class
                            $status_class = strtolower($order['order_status']);
                            if ($order['order_status'] === 'shipped') {
                                $status_class = 'shipping';
                            }
                            
                            // Button text based on status
                            $button_text = ($order['order_status'] === 'shipped') ? 'Track Order' : 'View Details';
                        ?>
                        <div class="order-item">
                            <div class="order-details">
                                <h3 class="order-id">Order #VV-<?php echo str_pad($order['order_id'], 4, '0', STR_PAD_LEFT); ?></h3>
                                <p class="order-date">Placed on <?php echo $order_date; ?></p>
                            </div>
                            <div class="order-status">
                                <span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($order['order_status']); ?></span>
                            </div>
                            <div class="order-amount">
                                <span class="amount">$<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                            <a href="order.php?order_id=<?php echo $order['order_id']; ?>" class="order-action-btn" style="text-decoration: none;"><?php echo $button_text; ?></a>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Saved Addresses Section -->
            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">Saved Addresses</h2>
                    <a href="address.php" class="add-new-btn" style="text-decoration: none;">
                        <i class='bx bx-plus'></i>
                        <span>Add New</span>
                    </a>
                </div>

                <div class="addresses-grid">
                    <?php if (empty($addresses)): ?>
                        <div class="empty-state">
                            <i class='bx bx-map'></i>
                            <p>No saved addresses yet. Add an address for faster checkout!</p>
                            <a href="address.php" class="btn-primary">Add Address</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($addresses as $address): ?>
                        <div class="address-card <?php echo $address['is_default'] ? 'default' : ''; ?>">
                            <?php if ($address['is_default']): ?>
                                <div class="address-badge">Default</div>
                            <?php endif; ?>
                            <h3 class="address-title"><?php echo htmlspecialchars($address['address_type']); ?></h3>
                            <p class="address-text">
                                <?php echo htmlspecialchars($address['address_line1']); ?>
                                <?php if (!empty($address['address_line2'])): ?>
                                    , <?php echo htmlspecialchars($address['address_line2']); ?>
                                <?php endif; ?><br>
                                <?php echo htmlspecialchars($address['city']); ?>, <?php echo htmlspecialchars($address['state']); ?> <?php echo htmlspecialchars($address['zip_code']); ?><br>
                                <?php echo htmlspecialchars($address['country']); ?>
                            </p>
                            <div class="address-actions">
                                <a href="address.php?edit=<?php echo $address['address_id']; ?>" class="address-btn edit">
                                    <i class='bx bx-edit'></i>
                                    <span>Edit</span>
                                </a>
                                <button class="address-btn delete" onclick="deleteAddress(<?php echo $address['address_id']; ?>)">
                                    <i class='bx bx-trash'></i>
                                    <span>Delete</span>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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