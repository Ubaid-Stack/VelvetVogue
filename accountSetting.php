
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
                
                <!-- Account Settings Container -->
                <div class="account-settings-container">
                    
                    <div class="account-settings-header">
                        <h1>Account Settings</h1>
                    </div>

                    <!-- Change Password Section -->
                    <div class="settings-section">
                        <div class="section-icon-header">
                            <div class="section-icon-wrapper">
                                <i class='bx bx-lock-alt'></i>
                            </div>
                            <div class="section-text">
                                <h2>Change Password</h2>
                                <p>Update your password to keep your account secure</p>
                            </div>
                        </div>

                        <form class="settings-form" id="passwordForm" onsubmit="updatePassword(event)">
                            <div class="form-group">
                                <label for="currentPassword">
                                    Current Password
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="currentPassword" name="currentPassword" placeholder="Enter current password" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword('currentPassword')">
                                        <i class='bx bx-hide'></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="newPassword">
                                    New Password
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password" required minlength="8">
                                    <button type="button" class="toggle-password" onclick="togglePassword('newPassword')">
                                        <i class='bx bx-hide'></i>
                                    </button>
                                </div>
                                <small class="form-hint">Must be at least 8 characters</small>
                            </div>

                            <div class="form-group">
                                <label for="confirmPassword">
                                    Confirm New Password
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">
                                        <i class='bx bx-hide'></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="settings-submit-btn">
                                <i class='bx bx-check-circle'></i>
                                <span>Update Password</span>
                            </button>
                        </form>
                    </div>

                    <!-- Email Notifications Section -->
                    <div class="settings-section">
                        <div class="section-icon-header">
                            <div class="section-icon-wrapper notification">
                                <i class='bx bx-bell'></i>
                            </div>
                            <div class="section-text">
                                <h2>Email Notifications</h2>
                                <p>Manage your email notification preferences</p>
                            </div>
                        </div>

                        <div class="notification-settings">
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h3>Order Updates</h3>
                                    <p>Receive updates about your order status</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked onchange="toggleNotification('order')">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="notification-item">
                                <div class="notification-info">
                                    <h3>Promotions & Offers</h3>
                                    <p>Get notified about special deals and discounts</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked onchange="toggleNotification('promo')">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="notification-item">
                                <div class="notification-info">
                                    <h3>Newsletter</h3>
                                    <p>Stay updated with our latest collections</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" onchange="toggleNotification('newsletter')">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="notification-item">
                                <div class="notification-info">
                                    <h3>Account Activity</h3>
                                    <p>Get alerts about important account changes</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked onchange="toggleNotification('activity')">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy & Security Section -->
                    <div class="settings-section">
                        <div class="section-icon-header">
                            <div class="section-icon-wrapper security">
                                <i class='bx bx-shield-quarter'></i>
                            </div>
                            <div class="section-text">
                                <h2>Privacy & Security</h2>
                                <p>Control your privacy and security settings</p>
                            </div>
                        </div>

                        <div class="privacy-settings">
                            <button class="privacy-action-btn" onclick="enableTwoFactor()">
                                <i class='bx bx-key'></i>
                                <div class="privacy-btn-text">
                                    <h3>Two-Factor Authentication</h3>
                                    <p>Add an extra layer of security</p>
                                </div>
                                <i class='bx bx-chevron-right'></i>
                            </button>

                            <button class="privacy-action-btn" onclick="manageDevices()">
                                <i class='bx bx-devices'></i>
                                <div class="privacy-btn-text">
                                    <h3>Connected Devices</h3>
                                    <p>Manage devices logged into your account</p>
                                </div>
                                <i class='bx bx-chevron-right'></i>
                            </button>

                            <button class="privacy-action-btn" onclick="downloadData()">
                                <i class='bx bx-download'></i>
                                <div class="privacy-btn-text">
                                    <h3>Download Your Data</h3>
                                    <p>Get a copy of your account data</p>
                                </div>
                                <i class='bx bx-chevron-right'></i>
                            </button>
                        </div>
                    </div>

                    <!-- Danger Zone Section -->
                    <div class="settings-section danger-section">
                        <div class="section-icon-header">
                            <div class="section-icon-wrapper danger">
                                <i class='bx bx-error-circle'></i>
                            </div>
                            <div class="section-text">
                                <h2>Danger Zone</h2>
                                <p>Irreversible actions that affect your account</p>
                            </div>
                        </div>

                        <div class="danger-actions">
                            <button class="danger-action-btn" onclick="deactivateAccount()">
                                <div class="danger-btn-content">
                                    <h3>Deactivate Account</h3>
                                    <p>Temporarily disable your account</p>
                                </div>
                                <i class='bx bx-chevron-right'></i>
                            </button>

                            <button class="danger-action-btn delete" onclick="deleteAccount()">
                                <div class="danger-btn-content">
                                    <h3>Delete Account</h3>
                                    <p>Permanently delete your account and data</p>
                                </div>
                                <i class='bx bx-chevron-right'></i>
                            </button>
                        </div>
                    </div>

                </div>

            </main>

        </div>

    </section>

    <?php include 'inc/footer.php'; ?>

    <script>
        // Toggle Mobile Sidebar
        const profileMenuToggle = document.getElementById('profileMenuToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const profileSidebar = document.querySelector('.profile-sidebar');

        profileMenuToggle.addEventListener('click', function() {
            profileSidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', function() {
            profileSidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });

        // Toggle Password Visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                field.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }

        // Update Password
        function updatePassword(event) {
            event.preventDefault();
            
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                Swal.fire({
                    title: 'Error!',
                    text: 'New passwords do not match',
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
                return;
            }

            if (newPassword.length < 8) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Password must be at least 8 characters',
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
                return;
            }

            // Simulate API call
            Swal.fire({
                title: 'Updating Password...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                Swal.fire({
                    title: 'Success!',
                    text: 'Your password has been updated successfully',
                    icon: 'success',
                    confirmButtonColor: '#3C91E6'
                });
                document.getElementById('passwordForm').reset();
            }, 1500);
        }

        // Toggle Notification
        function toggleNotification(type) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Notification preference updated',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        }

        // Enable Two-Factor Authentication
        function enableTwoFactor() {
            Swal.fire({
                title: 'Enable Two-Factor Authentication',
                html: `
                    <p style="margin-bottom: 20px; color: #6B7280;">Add an extra layer of security to your account by requiring a verification code in addition to your password.</p>
                    <div style="text-align: left;">
                        <p style="font-weight: 600; margin-bottom: 10px;">Choose your verification method:</p>
                        <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; margin-bottom: 10px; cursor: pointer;">
                            <input type="radio" name="2fa-method" value="sms" style="margin-right: 10px;">
                            <div>
                                <strong>SMS Text Message</strong><br>
                                <small style="color: #6B7280;">+1 (555) 123-4567</small>
                            </div>
                        </label>
                        <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; cursor: pointer;">
                            <input type="radio" name="2fa-method" value="app" style="margin-right: 10px;">
                            <div>
                                <strong>Authenticator App</strong><br>
                                <small style="color: #6B7280;">Google Authenticator, Authy, etc.</small>
                            </div>
                        </label>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Continue',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3C91E6',
                cancelButtonColor: '#6B7280'
            });
        }

        // Manage Devices
        function manageDevices() {
            Swal.fire({
                title: 'Connected Devices',
                html: `
                    <div style="text-align: left;">
                        <div style="padding: 15px; border: 2px solid #E5E7EB; border-radius: 10px; margin-bottom: 12px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class='bx bx-laptop' style="font-size: 24px; color: #3C91E6; margin-right: 12px;"></i>
                                <div>
                                    <strong>Windows PC - Chrome</strong><br>
                                    <small style="color: #10B981;">● Active now</small>
                                </div>
                            </div>
                            <small style="color: #6B7280;">Last active: Just now • New York, USA</small>
                        </div>
                        <div style="padding: 15px; border: 2px solid #E5E7EB; border-radius: 10px; margin-bottom: 12px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class='bx bx-mobile' style="font-size: 24px; color: #3C91E6; margin-right: 12px;"></i>
                                <div>
                                    <strong>iPhone 13 - Safari</strong><br>
                                    <small style="color: #6B7280;">Last active 2 hours ago</small>
                                </div>
                            </div>
                            <small style="color: #6B7280;">Last active: 2 hours ago • New York, USA</small>
                        </div>
                    </div>
                `,
                confirmButtonText: 'Close',
                confirmButtonColor: '#3C91E6'
            });
        }

        // Download Data
        function downloadData() {
            Swal.fire({
                title: 'Download Your Data',
                text: 'We will prepare a copy of your account data and send it to your email address. This may take a few minutes.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Request Download',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3C91E6',
                cancelButtonColor: '#6B7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Request Sent!',
                        text: 'You will receive an email with your data shortly.',
                        icon: 'success',
                        confirmButtonColor: '#3C91E6'
                    });
                }
            });
        }

        // Deactivate Account
        function deactivateAccount() {
            Swal.fire({
                title: 'Deactivate Account?',
                text: 'Your account will be temporarily disabled. You can reactivate it anytime by logging back in.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Deactivate',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Account Deactivated',
                        text: 'Your account has been deactivated successfully.',
                        icon: 'success',
                        confirmButtonColor: '#3C91E6'
                    });
                }
            });
        }

        // Delete Account
        function deleteAccount() {
            Swal.fire({
                title: 'Delete Account?',
                html: `
                    <p style="color: #6B7280; margin-bottom: 20px;">This action cannot be undone. All your data will be permanently deleted.</p>
                    <input type="text" id="deleteConfirm" class="swal2-input" placeholder="Type DELETE to confirm" style="text-transform: uppercase;">
                `,
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Delete Account',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                preConfirm: () => {
                    const input = document.getElementById('deleteConfirm').value;
                    if (input !== 'DELETE') {
                        Swal.showValidationMessage('Please type DELETE to confirm');
                        return false;
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Account Deleted',
                        text: 'Your account and all data have been permanently deleted.',
                        icon: 'success',
                        confirmButtonColor: '#3C91E6'
                    });
                }
            });
        }
    </script>

