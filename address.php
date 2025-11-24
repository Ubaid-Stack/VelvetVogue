<?php   
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once './inc/db.php';

$user_id = $_SESSION['user_id'];

// Fetch all addresses for the user
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
?>
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
                    <?php if (empty($addresses)): ?>
                        <div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 40px;">
                            <i class='bx bx-map' style="font-size: 64px; color: #D1D5DB;"></i>
                            <p style="color: #6B7280; margin: 20px 0;">No saved addresses yet. Add an address for faster checkout!</p>
                            <button class="add-address-btn" onclick="openAddressModal()">
                                <i class='bx bx-plus'></i>
                                <span>Add Your First Address</span>
                            </button>
                        </div>
                    <?php else: ?>
                        <?php foreach ($addresses as $address): ?>
                        <div class="address-card-item <?php echo $address['is_default'] ? 'default' : ''; ?>">
                            <?php if ($address['is_default']): ?>
                                <div class="address-card-badge">Default</div>
                            <?php endif; ?>
                            
                            <div class="address-card-icon">
                                <i class='bx bxs-map'></i>
                            </div>

                            <div class="address-card-content">
                                <h3 class="address-name"><?php echo htmlspecialchars($address['full_name']); ?></h3>
                                <p class="address-phone"><?php echo htmlspecialchars($address['phone']); ?></p>
                                <p class="address-details">
                                    <?php echo htmlspecialchars($address['address_line1']); ?>
                                    <?php if (!empty($address['address_line2'])): ?>
                                        , <?php echo htmlspecialchars($address['address_line2']); ?>
                                    <?php endif; ?><br>
                                    <?php echo htmlspecialchars($address['city']); ?>, <?php echo htmlspecialchars($address['state']); ?> <?php echo htmlspecialchars($address['zip_code']); ?><br>
                                    <?php echo htmlspecialchars($address['country']); ?>
                                </p>
                            </div>

                            <div class="address-card-actions">
                                <button class="address-action-btn edit" onclick="editAddress(<?php echo $address['address_id']; ?>)">
                                    <i class='bx bx-edit'></i>
                                    <span>Edit</span>
                                </button>
                                <button class="address-action-btn delete" onclick="deleteAddress(<?php echo $address['address_id']; ?>)">
                                    <i class='bx bx-trash'></i>
                                    <span>Delete</span>
                                </button>
                                <?php if (!$address['is_default']): ?>
                                <button class="address-action-btn default" onclick="setDefault(<?php echo $address['address_id']; ?>)">
                                    Set as Default
                                </button>
                                <?php endif; ?>
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
            <input type="hidden" id="addressId" name="address_id" value="">
            <input type="hidden" id="addressAction" name="action" value="add">
            
            <div class="form-group">
                <label for="addressType">Address Type</label>
                <select id="addressType" name="address_type" required>
                    <option value="home">Home</option>
                    <option value="work">Work</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
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
                    <input type="text" id="address1" name="address_line1" placeholder="Street address" required>
                </div>

                <div class="form-group">
                    <label for="address2">Address Line 2</label>
                    <input type="text" id="address2" name="address_line2" placeholder="Apt, suite, unit (optional)">
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
                        <option value="LK">Sri Lanka</option>
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
        document.getElementById('addressId').value = '';
        document.getElementById('addressAction').value = 'add';
        document.body.style.overflow = 'hidden';
    }

    function closeAddressModal() {
        document.getElementById('addressModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    function editAddress(id) {
        // Fetch address data
        const formData = new FormData();
        formData.append('action', 'get');
        formData.append('address_id', id);
        
        fetch('manage_address.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const address = data.address;
                document.getElementById('addressId').value = address.address_id;
                document.getElementById('addressAction').value = 'update';
                document.getElementById('addressType').value = address.address_type;
                document.getElementById('fullName').value = address.full_name;
                document.getElementById('phone').value = address.phone;
                document.getElementById('address1').value = address.address_line1;
                document.getElementById('address2').value = address.address_line2 || '';
                document.getElementById('city').value = address.city;
                document.getElementById('state').value = address.state;
                document.getElementById('zipCode').value = address.zip_code;
                document.getElementById('country').value = address.country;
                document.getElementById('setAsDefault').checked = address.is_default == 1;
                
                document.getElementById('modalTitle').textContent = 'Edit Address';
                document.getElementById('addressModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to load address',
                    confirmButtonColor: '#EF4444'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while loading the address',
                confirmButtonColor: '#EF4444'
            });
        });
    }

    function deleteAddress(id) {
        Swal.fire({
            title: 'Delete Address?',
            text: 'Are you sure you want to delete this address? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('address_id', id);
                
                fetch('manage_address.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message,
                            confirmButtonColor: '#3C91E6'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to delete address',
                            confirmButtonColor: '#EF4444'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while deleting the address',
                        confirmButtonColor: '#EF4444'
                    });
                });
            }
        });
    }

    function setDefault(id) {
        const formData = new FormData();
        formData.append('action', 'set_default');
        formData.append('address_id', id);
        
        fetch('manage_address.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to set default address',
                    confirmButtonColor: '#EF4444'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred',
                confirmButtonColor: '#EF4444'
            });
        });
    }

    function saveAddress(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        formData.set('set_default', document.getElementById('setAsDefault').checked ? 'true' : 'false');
        
        // Show loading
        Swal.fire({
            title: 'Saving Address...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch('manage_address.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    confirmButtonColor: '#3C91E6'
                }).then(() => {
                    closeAddressModal();
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to save address',
                    confirmButtonColor: '#EF4444'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while saving the address',
                confirmButtonColor: '#EF4444'
            });
        });
    }

    // Close modal when clicking outside
    document.getElementById('addressModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddressModal();
        }
    });
</script>

<?php include './inc/footer.php'; ?>
