<?php   
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once './inc/db.php';

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    $profile_image_path = null;
    
    // Validate username uniqueness if it's being changed
    if (!empty($username)) {
        $checkUsernameQuery = "SELECT user_id FROM users WHERE username = ? AND user_id != ?";
        $checkStmt = $conn->prepare($checkUsernameQuery);
        $checkStmt->bind_param("si", $username, $user_id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        if ($checkResult->num_rows > 0) {
            $error_message = 'Username already taken. Please choose another one.';
        }
        $checkStmt->close();
    }
    
    // Handle profile image upload
    if (!$error_message && isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_photo'];
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        // Validate file type
        if (!in_array($file['type'], $allowed_types)) {
            $error_message = 'Invalid file type. Only JPG, PNG, GIF, and WEBP images are allowed.';
        }
        // Validate file size
        elseif ($file['size'] > $max_size) {
            $error_message = 'File size too large. Maximum size is 5MB.';
        }
        else {
            // Create uploads directory if it doesn't exist
            $upload_dir = './images/profiles/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generate unique filename
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = 'profile_' . $user_id . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $profile_image_path = $upload_path;
                
                // Delete old profile image if exists and is not default
                $oldImageQuery = "SELECT profile_image FROM users WHERE user_id = ?";
                $oldImageStmt = $conn->prepare($oldImageQuery);
                $oldImageStmt->bind_param("i", $user_id);
                $oldImageStmt->execute();
                $oldImageResult = $oldImageStmt->get_result();
                $oldImage = $oldImageResult->fetch_assoc();
                $oldImageStmt->close();
                
                if (!empty($oldImage['profile_image']) && file_exists($oldImage['profile_image']) && strpos($oldImage['profile_image'], 'default') === false) {
                    unlink($oldImage['profile_image']);
                }
            } else {
                $error_message = 'Failed to upload image. Please try again.';
            }
        }
    }
    
    // Update user information
    if (!$error_message) {
        if ($profile_image_path) {
            // Update with new profile image
            $updateQuery = "UPDATE users SET full_name = ?, username = ?, email = ?, phone = ?, profile_image = ? WHERE user_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("sssssi", $full_name, $username, $email, $phone, $profile_image_path, $user_id);
        } else {
            // Update without changing profile image
            $updateQuery = "UPDATE users SET full_name = ?, username = ?, email = ?, phone = ? WHERE user_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ssssi", $full_name, $username, $email, $phone, $user_id);
        }
        
        if ($updateStmt->execute()) {
            $success_message = 'Profile updated successfully!';
            $_SESSION['full_name'] = $full_name;
            $_SESSION['username'] = $username;
        } else {
            $error_message = 'Failed to update profile. Please try again.';
        }
        $updateStmt->close();
    }
}

// Fetch current user data
$userQuery = "SELECT full_name, username, email, phone, profile_image FROM users WHERE user_id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
$userStmt->close();

$profile_image = !empty($user['profile_image']) ? $user['profile_image'] : './images/default-avatar.png';
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

        <!-- Edit Profile Main Content -->
        <main class="profile-main">
            <div class="edit-profile-container">
                <h1 class="page-title">Edit Profile</h1>
                
                <?php if ($success_message): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: '<?php echo $success_message; ?>',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: '<?php echo $error_message; ?>',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>
                <?php endif; ?>

                <form class="edit-profile-form" action="" method="post" enctype="multipart/form-data">
                    <!-- Profile Photo Section -->
                    <div class="photo-upload-section">
                        <div class="current-photo">
                            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Photo" id="photoPreview" onerror="this.src='./images/product1.jpg'">
                            <button type="button" class="change-photo-btn" onclick="document.getElementById('photoInput').click()">
                                <i class='bx bx-camera'></i>
                            </button>
                        </div>
                        <input type="file" id="photoInput" name="profile_photo" accept="image/*" style="display: none;">
                        <p class="photo-upload-text">Click to upload new photo</p>
                    </div>

                    <!-- Form Fields -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fullName">Full Name</label>
                            <input type="text" id="fullName" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="save-btn">
                            <i class='bx bx-save'></i>
                            <span>Save Changes</span>
                        </button>
                        <a href="profile.php" class="cancel-btn">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</section>
</main>
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

    // Photo Preview with validation
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please select a valid image file (JPG, PNG, GIF, or WEBP)',
                    confirmButtonColor: '#EF4444'
                });
                this.value = '';
                return;
            }
            
            // Validate file size (5MB)
            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Image size must be less than 5MB',
                    confirmButtonColor: '#EF4444'
                });
                this.value = '';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
                Swal.fire({
                    icon: 'success',
                    title: 'Image Selected',
                    text: 'Click "Save Changes" to upload your new profile photo',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<?php include './inc/footer.php'; ?>
