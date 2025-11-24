<?php include './inc/header.php'; ?>
<?php include './inc/db.php'; ?>

    </main>
    <section class="login-con">
        <div class="login-card">
            <h2>Welcome Back to Velvet Vogue</h2>
            <form class="login-form" action="#" method="post">
                <label for="username">Username or Email:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username or email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                
                <div class="login-form-options">
                    <div class="remember-me">
                        <input type="checkbox" name="rememberMe" id="rememberMe">
                        <label for="rememberMe">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>

                <button type="submit" name="submitBtn" class="login-btn">Login</button>
            </form>
            <p class="signup-link">Don't have an account? <a href="register.php">Sign up here</a></p>
        </div>
    </section>

    <script>
        document.querySelector('.login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Show loading state
            const submitBtn = this.querySelector('.login-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Logging in...';
            submitBtn.disabled = true;
            
            fetch('process_login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful!',
                        text: 'Welcome back to Velvet Vogue',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true,
                        position: 'top-end'
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    </script>
<?php include './inc/footer.php'; ?>