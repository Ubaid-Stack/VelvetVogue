  <!-- Sidebar -->
        <aside class="profile-sidebar">
            <nav class="profile-nav">
                <a href="profile.php" class="profile-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                    <i class='bx bx-user'></i>
                    <span>My Profile</span>
                </a>
                <a href="editProfile.php" class="profile-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'editProfile.php' ? 'active' : ''; ?>">
                    <i class='bx bx-edit'></i>
                    <span>Edit Profile</span>
                </a>
                <a href="address.php" class="profile-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'address.php' ? 'active' : ''; ?>">
                    <i class='bx bx-map'></i>
                    <span>Manage Addresses</span>
                </a>
                <a href="order.php" class="profile-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'order.php' ? 'active' : ''; ?>">
                    <i class='bx bx-box'></i>
                    <span>My Orders</span>
                </a>
                <a href="accountSetting.php" class="profile-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'accountSetting.php' ? 'active' : ''; ?>">
                    <i class='bx bx-cog'></i>
                    <span>Account Settings</span>
                </a>
                <a href="#" onclick="confirmLogout(event)" class="profile-nav-item logout">
                    <i class='bx bx-log-out'></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>
        
        <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Logout?',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Logout',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        }
        </script>