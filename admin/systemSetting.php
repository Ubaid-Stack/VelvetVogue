<?php 
$pageTitle = 'System Settings';
$pageSubtitle = 'Configure your store settings and preferences';
?>
<?php include './inc/head.php'; ?>

<?php include './inc/sidbar.php'; ?>
    
<?php include './inc/topbar.php'; ?>

        <!-- Settings Section -->
        <section class="settings-section">
            
            <!-- Store Status Card -->
            <div class="store-status-card">
                <div class="status-icon">
                    <i class='bx bx-globe'></i>
                </div>
                <div class="status-info">
                    <h3 class="status-title">Store Status</h3>
                    <p class="status-description">Your store is live and accepting orders</p>
                </div>
                <div class="status-toggle">
                    <label class="toggle-switch">
                        <input type="checkbox" id="storeStatusToggle" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <!-- General Settings Card -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <i class='bx bx-cog'></i>
                    <h3>General Settings</h3>
                </div>
                <form id="generalSettingsForm" class="settings-form">
                    <div class="form-group">
                        <label for="siteName">Site Name</label>
                        <input type="text" id="siteName" value="Velvet Vogue" placeholder="Enter site name">
                    </div>

                    <div class="form-group">
                        <label for="contactEmail">Contact Email</label>
                        <input type="email" id="contactEmail" value="contact@velvetvogue.com" placeholder="Enter contact email">
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="timezone">Timezone</label>
                            <select id="timezone">
                                <option value="utc-5" selected>UTC-5 (EST)</option>
                                <option value="utc-4">UTC-4 (EDT)</option>
                                <option value="utc-6">UTC-6 (CST)</option>
                                <option value="utc-7">UTC-7 (MST)</option>
                                <option value="utc-8">UTC-8 (PST)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <select id="currency">
                                <option value="usd" selected>USD</option>
                                <option value="eur">EUR</option>
                                <option value="gbp">GBP</option>
                                <option value="cad">CAD</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save-settings">
                            <i class='bx bx-save'></i>
                            <span>Save General Settings</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Shipping & Payment Settings Grid -->
            <div class="settings-grid">
                
                <!-- Shipping Settings Card -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <i class='bx bx-package'></i>
                        <h3>Shipping Settings</h3>
                    </div>
                    <form id="shippingSettingsForm" class="settings-form">
                        <div class="form-group">
                            <label for="standardShipping">Standard Shipping Rate</label>
                            <div class="input-with-prefix">
                                <span class="input-prefix">$</span>
                                <input type="number" id="standardShipping" value="0.00" step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="expressShipping">Express Shipping Rate</label>
                            <div class="input-with-prefix">
                                <span class="input-prefix">$</span>
                                <input type="number" id="expressShipping" value="0.00" step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="freeShippingThreshold">Free Shipping Threshold</label>
                            <div class="input-with-prefix">
                                <span class="input-prefix">$</span>
                                <input type="number" id="freeShippingThreshold" value="100.00" step="0.01" min="0" placeholder="100.00">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save-settings">
                                <i class='bx bx-save'></i>
                                <span>Save Shipping</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Payment Settings Card -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <i class='bx bx-credit-card'></i>
                        <h3>Payment Settings</h3>
                    </div>
                    <div class="payment-methods">
                        <div class="payment-method-item">
                            <div class="payment-method-info">
                                <span class="payment-method-name">Credit Card</span>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" class="payment-toggle" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="payment-method-item">
                            <div class="payment-method-info">
                                <span class="payment-method-name">PayPal</span>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" class="payment-toggle" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="payment-method-item">
                            <div class="payment-method-info">
                                <span class="payment-method-name">Stripe</span>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" class="payment-toggle">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="payment-method-item">
                            <div class="payment-method-info">
                                <span class="payment-method-name">Cash on Delivery</span>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" class="payment-toggle">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Email Notifications Card -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <i class='bx bx-envelope'></i>
                    <h3>Email Notifications</h3>
                </div>
                <div class="notification-settings">
                    <div class="notification-item">
                        <div class="notification-info">
                            <h4>Order Confirmation</h4>
                            <p>Send email when a new order is placed</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" class="notification-toggle" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="notification-item">
                        <div class="notification-info">
                            <h4>Order Shipped</h4>
                            <p>Send email when order is shipped</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" class="notification-toggle" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="notification-item">
                        <div class="notification-info">
                            <h4>Low Stock Alert</h4>
                            <p>Notify admin when product stock is low</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" class="notification-toggle" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="notification-item">
                        <div class="notification-info">
                            <h4>New Customer Registration</h4>
                            <p>Notify admin when a new customer registers</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" class="notification-toggle">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

        </section>

    </main>

    <script>
        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const adminSidebar = document.getElementById('adminSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        mobileMenuBtn.addEventListener('click', function() {
            adminSidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', function() {
            adminSidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });

        // Store Status Toggle
        document.getElementById('storeStatusToggle').addEventListener('change', function() {
            const status = this.checked ? 'online' : 'offline';
            Toast.fire({
                icon: 'success',
                title: `Store is now ${status}`
            });
        });

        // General Settings Form
        document.getElementById('generalSettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            Toast.fire({
                icon: 'success',
                title: 'General settings saved successfully!'
            });
        });

        // Shipping Settings Form
        document.getElementById('shippingSettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            Toast.fire({
                icon: 'success',
                title: 'Shipping settings saved successfully!'
            });
        });

        // Payment Method Toggles
        document.querySelectorAll('.payment-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const method = this.closest('.payment-method-item').querySelector('.payment-method-name').textContent;
                const status = this.checked ? 'enabled' : 'disabled';
                
                Toast.fire({
                    icon: 'info',
                    title: `${method} ${status}`
                });
            });
        });

        // Notification Toggles
        document.querySelectorAll('.notification-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const notification = this.closest('.notification-item').querySelector('h4').textContent;
                const status = this.checked ? 'enabled' : 'disabled';
                
                Toast.fire({
                    icon: 'info',
                    title: `${notification} ${status}`
                });
            });
        });
    </script>

</body>
</html>