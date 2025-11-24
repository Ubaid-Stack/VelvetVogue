<?php include './inc/header.php'; ?>
<?php include './inc/db.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        // Insert into database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, user_type) VALUES (?, ?, ?, ?, 'customer')");
        $stmt->bind_param("ssss", $username, $email, $phone, $hashed_password);
        
        if ($stmt->execute()) {
            $toastMsg = "Account created successfully!";
            $toastType = "success";
        } else {
            $toastMsg = "Error creating account: " . $stmt->error;
            $toastType = "danger";
        }
        $stmt->close();
    } else {
        $toastMsg = "Passwords do not match!";
        $toastType = "danger";
    }
}
?>
<!-- this is register code page -->
    </main>
    <section class="register-con">
        <div class="register-card">
            <h2>Create New Account</h2>
            <?php if (isset($toastMsg)): ?>
                <script>
                    Swal.fire({
                        icon: '<?php echo $toastType == "success" ? "success" : "error"; ?>',
                        title: '<?php echo $toastType == "success" ? "Success!" : "Error!"; ?>',
                        text: '<?php echo $toastMsg; ?>',
                        showConfirmButton: true,
                        confirmButtonColor: '#3C91E6'
                    });
                </script>
            <?php endif; ?>
            <form class="register-form" action="#" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>

                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>

                <button type="submit" name="submitBtn" class="register-btn">Register</button>
            </form>
            <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </section>
<?php include './inc/footer.php'; ?>