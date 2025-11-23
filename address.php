<?php include './inc/header.php'; ?>

<section class="profile-con">
    <!-- Mobile Menu Toggle Button -->
    <button class="profile-menu-toggle" id="profileMenuToggle">
        <i class='bx bx-menu'></i>
        <span>Menu</span>
    </button>

    <!-- Overlay for mobile sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="profile-layout">
        <?php include './inc/sidebar.php'; ?>

        <!-- Manage Addresses Main Content -->
        <main class="profile-main">
            <div class="address-page-container">
                <div class="address-page-header">
                    <h1 class="page-title">Manage Addresses</h1>
                    <button class="add-address-btn" onclick="openAddressModal()">
                        <i class='bx bx-plus'></i>
                        <span>Add New Address</span>
                    </button>
                </div>

                <!-- Address Cards Grid -->
                <div class="address-cards-grid">
                    <!-- Address Card 1 - Default -->
                    <div class="address-card-item default">
                        <div class="address-card-badge">Default</div>
                        
                        <div class="address-card-icon">
                            <i class='bx bxs-map'></i>
                        </div>

                        <div class="address-card-content">
                            <h3 class="address-name">Sophia Martinez</h3>
                            <p class="address-phone">+1 (555) 123-4567</p>
                            <p class="address-details">
                                123 Fashion Avenue, Apt 4B<br>
                                New York, NY 10001<br>
                                United States
                            </p>
                        </div>

                        <div class="address-card-actions">
                            <button class="address-action-btn edit" onclick="editAddress(1)">
                                <i class='bx bx-edit'></i>
                                <span>Edit</span>
                            </button>
                            <button class="address-action-btn delete" onclick="deleteAddress(1)">
                                <i class='bx bx-trash'></i>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>

                    <!-- Address Card 2 -->
                    <div class="address-card-item">
                        <div class="address-card-icon">
                            <i class='bx bxs-map'></i>
                        </div>

                        <div class="address-card-content">
                            <h3 class="address-name">Sophia Martinez</h3>
                            <p class="address-phone">+1 (555) 123-4567</p>
                            <p class="address-details">
                                456 Style Street<br>
                                Los Angeles, CA 90001<br>
                                United States
                            </p>
                        </div>

                        <div class="address-card-actions">
                            <button class="address-action-btn edit" onclick="editAddress(2)">
                                <i class='bx bx-edit'></i>
                                <span>Edit</span>
                            </button>
                            <button class="address-action-btn delete" onclick="deleteAddress(2)">
                                <i class='bx bx-trash'></i>
                                <span>Delete</span>
                            </button>
                            <button class="address-action-btn default" onclick="setDefault(2)">
                                Set as Default
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</section>

<!-- Add/Edit Address Modal -->
<div class="address-modal" id="addressModal">
    <div class="address-modal-content">
        <div class="address-modal-header">
            <h2 class="address-modal-title" id="modalTitle">Add New Address</h2>
            <button class="address-modal-close" onclick="closeAddressModal()">
                <i class='bx bx-x'></i>
            </button>
        </div>

        <form class="address-form" id="addressForm" onsubmit="saveAddress(event)">
            <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" name="full_name" placeholder="Enter full name" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter phone number" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="address1">Address Line 1</label>
                    <input type="text" id="address1" name="address1" placeholder="Street address" required>
                </div>

                <div class="form-group">
                    <label for="address2">Address Line 2</label>
                    <input type="text" id="address2" name="address2" placeholder="Apt, suite, unit (optional)">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" placeholder="City" required>
                </div>

                <div class="form-group">
                    <label for="state">State/Province</label>
                    <input type="text" id="state" name="state" placeholder="State" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="zipCode">ZIP/Postal Code</label>
                    <input type="text" id="zipCode" name="zip_code" placeholder="ZIP code" required>
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <select id="country" name="country" required>
                        <option value="">Select Country</option>
                        <option value="US" selected>United States</option>
                        <option value="CA">Canada</option>
                        <option value="UK">United Kingdom</option>
                        <option value="AU">Australia</option>
                    </select>
                </div>
            </div>

            <div class="form-group checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" id="setAsDefault" name="set_default">
                    <span>Set as default address</span>
                </label>
            </div>

            <div class="address-modal-actions">
                <button type="submit" class="modal-save-btn">
                    <i class='bx bx-save'></i>
                    <span>Save Address</span>
                </button>
                <button type="button" class="modal-cancel-btn" onclick="closeAddressModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Profile Sidebar Toggle for Mobile/Tablet
    const menuToggle = document.getElementById('profileMenuToggle');
    const sidebar = document.querySelector('.profile-sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (menuToggle && sidebar && overlay) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
        
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

    // Address Modal Functions
    function openAddressModal() {
        document.getElementById('addressModal').classList.add('active');
        document.getElementById('modalTitle').textContent = 'Add New Address';
        document.getElementById('addressForm').reset();
        document.body.style.overflow = 'hidden';
    }

    function closeAddressModal() {
        document.getElementById('addressModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    function editAddress(id) {
        document.getElementById('addressModal').classList.add('active');
        document.getElementById('modalTitle').textContent = 'Edit Address';
        document.body.style.overflow = 'hidden';
        // Load address data here
    }

    function deleteAddress(id) {
        if (confirm('Are you sure you want to delete this address?')) {
            // Delete address logic here
            console.log('Deleting address:', id);
        }
    }

    function setDefault(id) {
        // Set as default logic here
        console.log('Setting as default:', id);
    }

    function saveAddress(event) {
        event.preventDefault();
        // Save address logic here
        closeAddressModal();
        alert('Address saved successfully!');
    }

    // Close modal when clicking outside
    document.getElementById('addressModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddressModal();
        }
    });
</script>

<?php include './inc/footer.php'; ?>
