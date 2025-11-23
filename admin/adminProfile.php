<?php 
$pageTitle = 'Admin Profile';
$pageSubtitle = 'Manage your account settings';
?>
<?php include './inc/head.php'; ?>

<?php include './inc/sidbar.php'; ?>
    
<?php include './inc/topbar.php'; ?>

        <!-- Profile Section -->
        <section class="profile-section">
            
            <!-- Profile Header Card -->
            <div class="profile-header-card">
                <div class="profile-avatar-large">
                    <span class="avatar-text">SA</span>
                </div>
                <div class="profile-header-info">
                    <h2 class="profile-name">Sarah Anderson</h2>
                    <p class="profile-email">sarah.anderson@velvetvogue.com</p>
                    <span class="role-badge">Super Admin</span>
                    <p class="last-login">Last login: Jan 15, 2024 at 2:30 PM</p>
                </div>
                <div class="profile-header-actions">
                    <button class="btn-profile-edit" id="editProfileBtn">
                        <i class='bx bx-edit'></i>
                        <span>Edit Profile</span>
                    </button>
                    <button class="btn-change-password" id="changePasswordBtn">
                        <i class='bx bx-key'></i>
                        <span>Change Password</span>
                    </button>
                </div>
            </div>

            <!-- Profile Details Card -->
            <div class="profile-details-card">
                <h3 class="card-title">Profile Details</h3>
                
                <form id="profileForm">
                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" value="Sarah" readonly>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" value="Anderson" readonly>
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" value="sarah.anderson@velvetvogue.com" readonly>
                        </div>
                        <div class="form-group">
                            <label for="mobileNumber">Mobile Number</label>
                            <input type="tel" id="mobileNumber" value="+1 (555) 123-4567" readonly>
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="dateOfBirth">Date of Birth</label>
                            <input type="date" id="dateOfBirth" value="1990-05-15" readonly>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" disabled>
                                <option value="female" selected>Female</option>
                                <option value="male">Male</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="profilePhoto">Profile Photo</label>
                            <div class="file-upload">
                                <input type="file" id="profilePhoto" accept="image/*" disabled>
                                <label for="profilePhoto" class="file-upload-label">
                                    <i class='bx bx-upload'></i>
                                    <span>Upload Photo</span>
                                </label>
                                <p class="file-upload-hint">JPG, PNG or GIF (Max 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions" style="display: none;" id="formActions">
                        <button type="button" class="btn-secondary" id="cancelEditBtn">Cancel</button>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>

            <!-- Activity Log Card -->
            <div class="activity-log-card">
                <h3 class="card-title">Recent Activity</h3>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon login">
                            <i class='bx bx-log-in'></i>
                        </div>
                        <div class="activity-details">
                            <p class="activity-title">Logged in</p>
                            <p class="activity-time">Jan 15, 2024 at 2:30 PM</p>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon update">
                            <i class='bx bx-edit'></i>
                        </div>
                        <div class="activity-details">
                            <p class="activity-title">Updated product #VV-234</p>
                            <p class="activity-time">Jan 15, 2024 at 11:15 AM</p>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon order">
                            <i class='bx bx-package'></i>
                        </div>
                        <div class="activity-details">
                            <p class="activity-title">Processed order #VV-10233</p>
                            <p class="activity-time">Jan 14, 2024 at 4:20 PM</p>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon create">
                            <i class='bx bx-plus-circle'></i>
                        </div>
                        <div class="activity-details">
                            <p class="activity-title">Added new product</p>
                            <p class="activity-time">Jan 14, 2024 at 10:45 AM</p>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon login">
                            <i class='bx bx-log-in'></i>
                        </div>
                        <div class="activity-details">
                            <p class="activity-title">Logged in</p>
                            <p class="activity-time">Jan 14, 2024 at 9:00 AM</p>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>

    <!-- Change Password Modal -->
    <div class="modal-overlay" id="changePasswordModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Change Password</h2>
                <button class="modal-close" onclick="closePasswordModal()">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" id="currentPassword" placeholder="Enter current password" required>
                    </div>

                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" placeholder="Enter new password" required>
                        <span class="input-hint">Must be at least 8 characters</span>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <input type="password" id="confirmPassword" placeholder="Re-enter new password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closePasswordModal()">Cancel</button>
                <button class="btn-primary" onclick="submitPasswordChange()">Update Password</button>
            </div>
        </div>
    </div>

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

        // Edit Profile Toggle
        let isEditing = false;
        const editProfileBtn = document.getElementById('editProfileBtn');
        const formInputs = document.querySelectorAll('#profileForm input, #profileForm select');
        const formActions = document.getElementById('formActions');
        const cancelEditBtn = document.getElementById('cancelEditBtn');

        editProfileBtn.addEventListener('click', function() {
            isEditing = true;
            formInputs.forEach(input => {
                input.removeAttribute('readonly');
                input.removeAttribute('disabled');
            });
            formActions.style.display = 'flex';
            editProfileBtn.style.display = 'none';
            
            Toast.fire({
                icon: 'info',
                title: 'Edit mode enabled'
            });
        });

        cancelEditBtn.addEventListener('click', function() {
            isEditing = false;
            formInputs.forEach(input => {
                input.setAttribute('readonly', 'true');
                if (input.tagName === 'SELECT') {
                    input.setAttribute('disabled', 'true');
                }
                if (input.type === 'file') {
                    input.setAttribute('disabled', 'true');
                }
            });
            formActions.style.display = 'none';
            editProfileBtn.style.display = 'flex';
            
            // Reset form values
            document.getElementById('firstName').value = 'Sarah';
            document.getElementById('lastName').value = 'Anderson';
            document.getElementById('email').value = 'sarah.anderson@velvetvogue.com';
            document.getElementById('mobileNumber').value = '+1 (555) 123-4567';
            document.getElementById('dateOfBirth').value = '1990-05-15';
            document.getElementById('gender').value = 'female';
            
            Toast.fire({
                icon: 'info',
                title: 'Changes cancelled'
            });
        });

        // Profile Form Submit
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Here you would send the data to your backend
            
            isEditing = false;
            formInputs.forEach(input => {
                input.setAttribute('readonly', 'true');
                if (input.tagName === 'SELECT') {
                    input.setAttribute('disabled', 'true');
                }
                if (input.type === 'file') {
                    input.setAttribute('disabled', 'true');
                }
            });
            formActions.style.display = 'none';
            editProfileBtn.style.display = 'flex';
            
            Toast.fire({
                icon: 'success',
                title: 'Profile updated successfully!'
            });
        });

        // Change Password Modal
        const changePasswordBtn = document.getElementById('changePasswordBtn');
        const changePasswordModal = document.getElementById('changePasswordModal');

        changePasswordBtn.addEventListener('click', function() {
            changePasswordModal.classList.add('active');
        });

        function closePasswordModal() {
            changePasswordModal.classList.remove('active');
            document.getElementById('changePasswordForm').reset();
        }

        function submitPasswordChange() {
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (!currentPassword || !newPassword || !confirmPassword) {
                Toast.fire({
                    icon: 'error',
                    title: 'Please fill in all fields'
                });
                return;
            }

            if (newPassword.length < 8) {
                Toast.fire({
                    icon: 'error',
                    title: 'Password must be at least 8 characters'
                });
                return;
            }

            if (newPassword !== confirmPassword) {
                Toast.fire({
                    icon: 'error',
                    title: 'Passwords do not match'
                });
                return;
            }

            // Here you would send the password change to your backend
            
            closePasswordModal();
            Toast.fire({
                icon: 'success',
                title: 'Password changed successfully!'
            });
        }

        // Profile Photo Upload
        document.getElementById('profilePhoto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    Toast.fire({
                        icon: 'error',
                        title: 'File size must be less than 2MB'
                    });
                    e.target.value = '';
                    return;
                }

                Toast.fire({
                    icon: 'success',
                    title: 'Photo selected: ' + file.name
                });
            }
        });
    </script>

</body>
</html>