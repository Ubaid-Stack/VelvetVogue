<!-- Main Content -->
    <main class="admin-main">
        
        <!-- Top Header -->
        <header class="admin-header">
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class='bx bx-menu'></i>
            </button>

            <div class="header-title">
                <h1><?php echo isset($pageTitle) ? $pageTitle : 'Admin Dashboard'; ?></h1>
                <p><?php echo isset($pageSubtitle) ? $pageSubtitle : 'Welcome to your admin panel'; ?></p>
            </div>

            <div class="header-actions">
                <button class="header-icon-btn">
                    <i class='bx bx-bell'></i>
                    <span class="notification-badge">3</span>
                </button>
                <button class="header-icon-btn">
                    <i class='bx bx-cog'></i>
                </button>
                <div class="header-profile">
                    <div class="profile-avatar">SA</div>
                    <div class="profile-info">
                        <span class="profile-name">Sarah Anderson</span>
                        <span class="profile-role">Administrator</span>
                    </div>
                </div>
            </div>
        </header>