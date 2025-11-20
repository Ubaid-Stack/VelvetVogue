<?php include './inc/header.php'; ?>
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
<?php include './inc/footer.php'; ?>