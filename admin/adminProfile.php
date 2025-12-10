<?php 
session_start();
require_once '../inc/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: adminLogin.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Helper: log admin activity (safe if activity_log table exists)
function logActivity($conn, $userId, $action) {
    try {
        $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action) VALUES (?, ?)");
        $stmt->bind_param("is", $userId, $action);
        $stmt->execute();
        $stmt->close();
    } catch (Exception $e) {
        // Silently ignore if logging fails
    }
}

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    
    // Handle profile photo upload
    $profile_image = null;
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_photo']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        $filesize = $_FILES['profile_photo']['size'];
        
        if (!in_array(strtolower($filetype), $allowed)) {
            $_SESSION['error'] = 'Only JPG, JPEG, PNG & GIF files are allowed';
        } elseif ($filesize > 2 * 1024 * 1024) {
            $_SESSION['error'] = 'File size must be less than 2MB';
        } else {
            $upload_dir = '../images/profiles/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $new_filename = 'admin_' . $user_id . '_' . time() . '.' . $filetype;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_path)) {
                $profile_image = './images/profiles/' . $new_filename;
            }
        }
    }
    
    // Update user profile
    if ($profile_image) {
        $updateQuery = "UPDATE users SET full_name = ?, email = ?, phone = ?, profile_image = ? WHERE user_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ssssi", $full_name, $email, $phone, $profile_image, $user_id);
    } else {
        $updateQuery = "UPDATE users SET full_name = ?, email = ?, phone = ? WHERE user_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sssi", $full_name, $email, $phone, $user_id);
    }
    
    if ($updateStmt->execute()) {
        $_SESSION['success'] = 'Profile updated successfully!';
        logActivity($conn, $user_id, 'Updated profile information');
    } else {
        $_SESSION['error'] = 'Failed to update profile';
    }
    $updateStmt->close();
    
    header('Location: adminProfile.php');
    exit();
}

// Handle Password Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Fetch current password hash
    $passQuery = "SELECT password FROM users WHERE user_id = ?";
    $passStmt = $conn->prepare($passQuery);
    $passStmt->bind_param("i", $user_id);
    $passStmt->execute();
    $passResult = $passStmt->get_result();
    $userData = $passResult->fetch_assoc();
    $passStmt->close();
    
    if (!password_verify($current_password, $userData['password'])) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
        exit();
    }
    
    if (strlen($new_password) < 8) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
        exit();
    }
    
    if ($new_password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
        exit();
    }
    
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $updatePassQuery = "UPDATE users SET password = ? WHERE user_id = ?";
    $updatePassStmt = $conn->prepare($updatePassQuery);
    $updatePassStmt->bind_param("si", $hashed_password, $user_id);
    
    if ($updatePassStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Password changed successfully!']);
        logActivity($conn, $user_id, 'Changed account password');
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to change password']);
    }
    $updatePassStmt->close();
    exit();
}

// Fetch admin profile data
$profileQuery = "SELECT full_name, username, email, phone, profile_image, user_type, created_at, last_login FROM users WHERE user_id = ?";
$profileStmt = $conn->prepare($profileQuery);
$profileStmt->bind_param("i", $user_id);
$profileStmt->execute();
$profileResult = $profileStmt->get_result();
$adminProfile = $profileResult->fetch_assoc();
$profileStmt->close();

// Fetch recent activity (last 5) — gracefully handle missing table
$activities = [];
try {
    $activityQuery = "SELECT action, created_at FROM activity_log WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
    $activityStmt = $conn->prepare($activityQuery);
    $activityStmt->bind_param("i", $user_id);
    $activityStmt->execute();
    $activityResult = $activityStmt->get_result();
    $activities = $activityResult->fetch_all(MYSQLI_ASSOC);
    $activityStmt->close();
} catch (Exception $e) {
    // If table doesn't exist, just show the fallback activity below.
    $activities = [];
}

// Split full name
$name_parts = explode(' ', $adminProfile['full_name'], 2);
$first_name = $name_parts[0] ?? '';
$last_name = $name_parts[1] ?? '';

// Get initials
if (count($name_parts) >= 2) {
    $initials = strtoupper(substr($name_parts[0], 0, 1) . substr($name_parts[1], 0, 1));
} else {
    $initials = strtoupper(substr($adminProfile['full_name'], 0, 2));
}

$pageTitle = 'Admin Profile';
$pageSubtitle = 'Manage your account settings';
?>
<?php include './inc/head.php'; ?>

<?php include './inc/sidbar.php'; ?>
    
<?php include './inc/topbar.php'; ?>

        <!-- Profile Section -->
        <section class="profile-section">
            
            <?php if (isset($_SESSION['success'])): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '<?php echo $_SESSION['success']; ?>',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                </script>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '<?php echo $_SESSION['error']; ?>',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                </script>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <!-- Profile Header Card -->
            <div class="profile-header-card">
                <div class="profile-avatar-large">
                    <?php if (!empty($adminProfile['profile_image'])): 
                        // Fix image path for admin folder
                        $image_path = str_replace('./images/', '../images/', $adminProfile['profile_image']);
                    ?>
                        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    <?php else: ?>
                        <span class="avatar-text"><?php echo $initials; ?></span>
                    <?php endif; ?>
                </div>
                <div class="profile-header-info">
                    <h2 class="profile-name"><?php echo htmlspecialchars($adminProfile['full_name']); ?></h2>
                    <p class="profile-email"><?php echo htmlspecialchars($adminProfile['email']); ?></p>
                    <span class="role-badge"><?php echo ucfirst($adminProfile['user_type']); ?></span>
                    <p class="last-login">Member since: <?php echo date('M d, Y', strtotime($adminProfile['created_at'])); ?></p>
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
                
                <form id="profileForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="update_profile" value="1">
                    
                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" readonly required>
                        </div>
                    </div>
                    
                    <input type="hidden" id="fullName" name="full_name" value="<?php echo htmlspecialchars($adminProfile['full_name']); ?>">

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($adminProfile['email']); ?>" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($adminProfile['phone'] ?? ''); ?>" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="profilePhoto">Profile Photo</label>
                            <div class="file-upload">
                                <input type="file" id="profilePhoto" name="profile_photo" accept="image/*" disabled>
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
                    <?php if (!empty($activities)): ?>
                        <?php foreach ($activities as $item): ?>
                            <div class="activity-item">
                                <div class="activity-icon login">
                                    <i class='bx bx-time'></i>
                                </div>
                                <div class="activity-details">
                                    <p class="activity-title"><?php echo htmlspecialchars($item['action']); ?></p>
                                    <p class="activity-time"><?php echo date('M d, Y \a\t g:i A', strtotime($item['created_at'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php if (!empty($adminProfile['last_login'])): ?>
                        <div class="activity-item">
                            <div class="activity-icon login">
                                <i class='bx bx-log-in'></i>
                            </div>
                            <div class="activity-details">
                                <p class="activity-title">Last login</p>
                                <p class="activity-time"><?php echo date('M d, Y \a\t g:i A', strtotime($adminProfile['last_login'])); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="activity-item">
                            <div class="activity-icon login">
                                <i class='bx bx-user-plus'></i>
                            </div>
                            <div class="activity-details">
                                <p class="activity-title">Account created</p>
                                <p class="activity-time"><?php echo date('M d, Y \a\t g:i A', strtotime($adminProfile['created_at'])); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </section>

    </main>

    <!-- Change Password Modal -->
    <div class="modal-overlay" id="changePasswordModal">
        <div class="modal-container password-modal">
            <div class="modal-header">
                <div class="modal-title-group">
                    <p class="eyebrow">Security</p>
                    <h2>Change Password</h2>
                    <p class="subtitle">Use a strong, unique password you don't use elsewhere.</p>
                </div>
                <button class="modal-close" onclick="closePasswordModal()" aria-label="Close password modal">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <div class="input-with-icon">
                            <i class='bx bx-lock-alt'></i>
                            <input type="password" id="currentPassword" placeholder="Enter current password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <div class="input-with-icon">
                            <i class='bx bx-key'></i>
                            <input type="password" id="newPassword" placeholder="Minimum 8 characters" required>
                        </div>
                        <span class="input-hint">Use at least 8 characters with a mix of letters, numbers, and symbols.</span>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <div class="input-with-icon">
                            <i class='bx bx-check-shield'></i>
                            <input type="password" id="confirmPassword" placeholder="Re-enter new password" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="modal-footer-actions">
                    <button class="btn-secondary" type="button" onclick="closePasswordModal()">Cancel</button>
                    <button class="btn-primary" type="button" onclick="submitPasswordChange()">
                        <i class='bx bx-save'></i>
                        Update Password
                    </button>
                </div>
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
            window.location.reload();
        });

        // Profile Form Submit - Update full_name before submission
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            document.getElementById('fullName').value = firstName + ' ' + lastName;
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

            // Send password change to backend
            const formData = new FormData();
            formData.append('change_password', '1');
            formData.append('current_password', currentPassword);
            formData.append('new_password', newPassword);
            formData.append('confirm_password', confirmPassword);
            
            fetch('adminProfile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closePasswordModal();
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                }
            })
            .catch(error => {
                Toast.fire({
                    icon: 'error',
                    title: 'An error occurred'
                });
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