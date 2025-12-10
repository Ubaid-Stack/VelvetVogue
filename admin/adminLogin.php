<?php
    session_start();
    include '../inc/db.php';
    
    $errorMsg = '';
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $stmt = $conn->prepare("SELECT user_id, password, user_type FROM users WHERE username = ? AND user_type = 'admin'");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows === 1){
            $stmt->bind_result($userId, $hashedPassword, $userType);
            $stmt->fetch();

            if($hashedPassword && password_verify($password, $hashedPassword)){
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;
                $_SESSION['user_type'] = $userType;
                $_SESSION['logged_in'] = true;
                
                // Update last login
                $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
                $updateStmt->bind_param('i', $userId);
                $updateStmt->execute();
                $updateStmt->close();

                // Log login activity if table exists
                try {
                    $logStmt = $conn->prepare("INSERT INTO activity_log (user_id, action) VALUES (?, ?)");
                    $action = 'Logged in';
                    $logStmt->bind_param('is', $userId, $action);
                    $logStmt->execute();
                    $logStmt->close();
                } catch (Exception $e) {
                    // ignore if logging fails
                }
                
                header("Location: dashboard.php");
                exit();
            } else {
                $errorMsg = 'Invalid username or password';
            }
        } else {
            $errorMsg = 'Invalid username or password';
        }
        $stmt->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - VelvetVogue</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-btn: #3C91E6;
            --primary-btn-hover: #2B6FBF;
            --text-head: #1E1E1E;
            --text-body: #444444;
            --border-color: #E0E0E0;
            --font-family-primary: 'Montserrat', sans-serif;
            --font-family-secondary: 'Open Sans', sans-serif;
            --bg-light: #f8f9fa;
            --gradient-primary: linear-gradient(135deg, #3C91E6, #2B6FBF);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-family-secondary);
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr;
        }

        .login-banner {
            background: var(--gradient-primary);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #FFFFFF;
        }

        .login-banner i {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .login-banner h1 {
            font-family: var(--font-family-primary);
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .login-banner p {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .login-form-section {
            padding: 60px 40px;
        }

        .form-header {
            margin-bottom: 40px;
            text-align: center;
        }

        .form-header h2 {
            font-family: var(--font-family-primary);
            font-size: 28px;
            font-weight: 700;
            color: var(--text-head);
            margin-bottom: 8px;
        }

        .form-header p {
            font-size: 15px;
            color: var(--text-body);
        }

        .login-form {
            max-width: 400px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-family: var(--font-family-primary);
            font-size: 14px;
            font-weight: 600;
            color: var(--text-head);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .bx-user,
        .input-wrapper .bx-lock-alt {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-body);
            font-size: 20px;
            pointer-events: none;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-family: var(--font-family-secondary);
            font-size: 15px;
            color: var(--text-head);
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-btn);
            box-shadow: 0 0 0 4px rgba(60, 145, 230, 0.1);
        }

        .form-group input::placeholder {
            color: #999;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-body);
            font-size: 20px;
            transition: color 0.3s ease;
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--primary-btn);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-body);
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary-btn);
        }

        .forgot-password {
            color: var(--primary-btn);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-btn-hover);
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-btn);
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            font-family: var(--font-family-primary);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(60, 145, 230, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-btn:hover {
            background-color: var(--primary-btn-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(60, 145, 230, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .back-to-site {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }

        .back-to-site a {
            color: var(--text-body);
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.3s ease;
        }

        .back-to-site a:hover {
            color: var(--primary-btn);
        }

        .back-to-site a i {
            font-size: 18px;
        }

        /* Responsive Design */
        @media (min-width: 768px) {
            .login-container {
                grid-template-columns: 1fr 1fr;
            }

            .login-banner {
                padding: 80px 50px;
            }

            .login-form-section {
                padding: 80px 50px;
            }
        }

        @media (max-width: 767px) {
            .login-banner {
                display: none;
            }

            .login-form-section {
                padding: 40px 24px;
            }

            .form-header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Banner -->
        <div class="login-banner">
            <i class='bx bxs-shopping-bag'></i>
            <h1>Velvet Vogue</h1>
            <p>Admin Dashboard Access<br>Manage your e-commerce store with ease</p>
        </div>

        <!-- Right Login Form -->
        <div class="login-form-section">
            <div class="form-header">
                <h2>Welcome Back!</h2>
                <p>Sign in to access the admin dashboard</p>
            </div>

            <form class="login-form" id="adminLoginForm" method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <i class='bx bx-user'></i>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required style="padding-right: 48px;">
                        <i class='bx bx-hide password-toggle' id="togglePassword"></i>
                    </div>
                </div>

                <?php if(!empty($errorMsg)): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: '<?php echo htmlspecialchars($errorMsg); ?>',
                            confirmButtonColor: '#3C91E6',
                            confirmButtonText: 'Try Again'
                        });
                    });
                </script>
                <?php endif; ?>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>

                <button type="submit" class="login-btn">
                    <i class='bx bx-log-in'></i>
                    <span>Sign In</span>
                </button>

                <div class="back-to-site">
                    <a href="../index.php">
                        <i class='bx bx-arrow-back'></i>
                        <span>Back to Website</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Password Toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('bx-hide');
            this.classList.toggle('bx-show');
        });

        // Form Submission
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            // Let the form submit naturally to PHP
            // Remove preventDefault to allow PHP processing
        });
    </script>
</body>
</html>
