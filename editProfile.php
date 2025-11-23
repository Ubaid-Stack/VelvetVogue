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

                <form class="edit-profile-form" action="#" method="post" enctype="multipart/form-data">
                    <!-- Profile Photo Section -->
                    <div class="photo-upload-section">
                        <div class="current-photo">
                            <img src="./images/womensWear.webp" alt="Profile Photo" id="photoPreview" onerror="this.src='./images/product1.jpg'">
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
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="first_name" value="Sophia" required>
                        </div>

                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" name="last_name" value="Martinez" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="sophia.martinez@example.com" required>
                        </div>

                        <div class="form-group">
                            <label for="mobile">Mobile Number</label>
                            <input type="tel" id="mobile" name="mobile" value="+1 (555) 123-4567" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob" value="1992-06-15">
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female" selected>Female</option>
                                <option value="other">Other</option>
                            </select>
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

    // Photo Preview
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<?php include './inc/footer.php'; ?>
