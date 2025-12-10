<!-- Main Content -->
    <main class="admin-main">
        
        <?php
        // Fetch admin user details
        if (isset($_SESSION['user_id'])) {
            $admin_id = $_SESSION['user_id'];
            $adminQuery = "SELECT full_name, username, user_type FROM users WHERE user_id = ?";
            $adminStmt = $conn->prepare($adminQuery);
            $adminStmt->bind_param("i", $admin_id);
            $adminStmt->execute();
            $adminResult = $adminStmt->get_result();
            
            if ($adminResult->num_rows > 0) {
                $adminData = $adminResult->fetch_assoc();
                $admin_name = $adminData['full_name'] ?? $adminData['username'];
                $admin_role = ucfirst($adminData['user_type']);
                
                // Get initials for avatar
                $name_parts = explode(' ', $admin_name);
                $initials = '';
                if (count($name_parts) >= 2) {
                    $initials = strtoupper(substr($name_parts[0], 0, 1) . substr($name_parts[1], 0, 1));
                } else {
                    $initials = strtoupper(substr($admin_name, 0, 2));
                }
            } else {
                $admin_name = 'Admin User';
                $admin_role = 'Administrator';
                $initials = 'AD';
            }
            $adminStmt->close();
        } else {
            $admin_name = 'Admin User';
            $admin_role = 'Administrator';
            $initials = 'AD';
        }
        ?>
        
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
                <a href="systemSetting.php" style="text-decoration: none;">
                    <button class="header-icon-btn">
                    <i class='bx bx-cog'></i>
                    </button>
                </a>
                <div class="header-profile">
                    <div class="profile-avatar"><?php echo $initials; ?></div>
                    <div class="profile-info">
                        <span class="profile-name"><?php echo htmlspecialchars($admin_name); ?></span>
                        <span class="profile-role"><?php echo htmlspecialchars($admin_role); ?></span>
                    </div>
                </div>
            </div>
        </header>