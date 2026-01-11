<?php
require_once 'config/db.php';

// Migration: Ensure phone exists in users table
try {
    $check = $pdo->query("SHOW COLUMNS FROM users LIKE 'phone'")->fetch();
    if (!$check) {
        $pdo->exec("ALTER TABLE users ADD COLUMN phone VARCHAR(20) UNIQUE AFTER email");
    }
} catch (Exception $e) {}

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    $role = $_SESSION['role'];
    header("Location: $role/dashboard.php");
    exit();
}
?>
<?php
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_input = trim($_POST['username']); 
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$login_input, $login_input]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['full_name'];
        session_write_close();
        header("Location: " . $user['role'] . "/dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - College Transport Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="login-body">
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    </script>

    <?php if ($error): ?>
        <script>
            Toast.fire({
                icon: 'error',
                title: 'Login Failed',
                text: '<?php echo $error; ?>'
            });
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['logout_success'])): ?>
        <script>
            Toast.fire({
                icon: 'success',
                title: 'Logged Out',
                text: 'Session ended successfully'
            });
        </script>
    <?php endif; ?>

    <div class="login-container" style="max-width: 450px;">
        <div class="login-box" style="display: block;">

            <!-- Right Side: Login Form -->
            <div class="login-form-side">
                <div class="login-header">
                    <h1>Welcome Back</h1>
                    <p>Enter your details to stay connected</p>
                </div>

                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username or Email</label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="username" name="username" placeholder="Enter username" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-icon" style="position: relative;">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="Enter password" required style="padding-right: 2.5rem;">
                            <i class="fas fa-eye toggle-password" onclick="togglePass('password', this)" style="position: absolute; right: 1rem; left: auto; cursor: pointer; color: var(--text-muted); font-size: 0.8rem; top: 50%; transform: translateY(-50%);"></i>
                        </div>
                    </div>

                    <div class="form-actions">
                        <label class="remember">
                            <input type="checkbox"> <span>Remember me</span>
                        </label>
                        <a href="forgot-password.php" class="forgot-pass">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-login">
                        <span>Sign In</span>
                        <i class="fas fa-arrow-right" style="margin-left: 10px;"></i>
                    </button>
                </form>

                <div class="login-footer">
                    <p>Don't have an account? <a href="register.php">Register Now</a></p>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePass(id, el) {
            const input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
                el.classList.remove('fa-eye');
                el.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                el.classList.remove('fa-eye-slash');
                el.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
