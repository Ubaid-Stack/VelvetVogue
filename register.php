<?php include './inc/header.php'; ?>
<!-- this is register code page -->
    </main>
    <section class="register-con">
        <div class="register-card">
            <h2>Create New Account</h2>
            <form class="register-form" action="#" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>

                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>

                <button type="submit" name="submitBtn" class="register-btn">Register</button>
            </form>
            <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </section>
<?php include './inc/footer.php'; ?>