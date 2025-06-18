<?php
session_start();
require_once 'includes/db.php';

$error = '';
$success = '';

// Check if admin is already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: admin/dashboard.php');
    exit();
}

// Check for logout message
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    $success = 'Anda telah berhasil logout dari sistem admin.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        // Log login attempt
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_login_time'] = time();
            $_SESSION['admin_ip'] = $ip_address;
            
            // Handle remember me functionality
            if ($remember_me) {
                $token = bin2hex(random_bytes(32));
                setcookie('admin_remember_token', $token, time() + (7 * 24 * 60 * 60), '/'); // 7 days
            }
            
            // Redirect to admin dashboard
            header('Location: admin/dashboard.php');
            exit();
        } else {
            $error = 'Username atau password salah!';
            
            // Log failed attempt
            error_log("Failed admin login attempt for username: $username from IP: $ip_address");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - KostQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/admin/login_admin.css">
    
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7 col-sm-9">
                <div class="login-container">
                    <!-- Header Section -->
                    <div class="login-header">
                        <div class="brand-logo">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h1 class="login-title">Admin Panel</h1>
                        <p class="login-subtitle">Masuk ke sistem administrasi KostQ</p>
                    </div>
                    
                    <!-- Body Section -->
                    <div class="login-body">
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-eco">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-eco">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $success; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" id="loginForm">
                            <!-- Username Input -->
                            <div class="input-group">
                                <i class="fas fa-user-tie input-icon"></i>
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="Username Administrator" required 
                                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                            </div>
                            
                            <!-- Password Input -->
                            <div class="input-group">
                                <i class="fas fa-key input-icon"></i>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Password Administrator" required>
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                            
                            <!-- Login Button -->
                            <button type="submit" class="btn-login" id="loginBtn">
                                <div class="loading-spinner"></div>
                                <span class="btn-text">
                                    <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Admin Panel
                                </span>
                            </button>
                        </form>
                        
                        
                        <!-- Auth Links -->
                        <div class="auth-links">
                            <a href="login_user.php" class="auth-link">
                                <i class="fas fa-user me-1"></i>
                                Login sebagai User
                            </a>
                            <br>
                            <a href="#" class="auth-link" onclick="showAdminHelp()">
                                <i class="fas fa-question-circle me-1"></i>
                                Bantuan Admin
                            </a>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password toggle functionality
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        // Input focus effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });

        // Auto-focus on username field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });

        // Enter key handling
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('loginForm').submit();
            }
        });

        // Smooth animations on load
        window.addEventListener('load', function() {
            document.querySelector('.login-container').style.opacity = '0';
            document.querySelector('.login-container').style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                document.querySelector('.login-container').style.transition = 'all 0.6s ease';
                document.querySelector('.login-container').style.opacity = '1';
                document.querySelector('.login-container').style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
