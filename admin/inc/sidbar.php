<!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <h2 class="sidebar-logo">Velvet Vogue</h2>
            <p class="sidebar-subtitle">Admin Dashboard</p>
        </div>

        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                <i class='bx bx-grid-alt'></i>
                <span>Dashboard</span>
            </a>
            <a href="manageProduct.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'manageProduct.php') ? 'active' : ''; ?>">
                <i class='bx bx-package'></i>
                <span>Manage Products</span>
            </a>
            <a href="manageOrder.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'manageOrder.php') ? 'active' : ''; ?>">
                <i class='bx bx-shopping-bag'></i>
                <span>Manage Orders</span>
            </a>
            <a href="adminProfile.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'adminProfile.php') ? 'active' : ''; ?>">
                <i class='bx bx-user-circle'></i>
                <span>Admin Profile</span>
            </a>
            <a href="createAdmin.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'createAdmin.php') ? 'active' : ''; ?>">
                <i class='bx bx-user-plus'></i>
                <span>Create Admin</span>
            </a>
            <a href="systemSetting.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'systemSetting.php') ? 'active' : ''; ?>">
                <i class='bx bx-cog'></i>
                <span>System Settings</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="../logout.php" class="nav-item logout">
                <i class='bx bx-log-out'></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

