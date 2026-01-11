<?php
require_once 'config/db.php';

if (isLoggedIn()) {
    $role = $_SESSION['role'];
    header("Location: $role/dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ? OR phone = ?");
        $stmt->execute([$username, $email, $phone]);
        if ($stmt->fetch()) {
            $error = "Username, Email, or Phone already exists!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role, full_name, email, phone) VALUES (?, ?, 'student', ?, ?, ?)");
            
            if ($stmt->execute([$username, $hashed_password, $full_name, $email, $phone])) {
                $success = "Registration successful! Redirecting to login...";
                header("refresh:2;url=login.php");
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - College Transport System</title>
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
                title: 'Registration Error',
                text: '<?php echo $error; ?>'
            });
        </script>
    <?php endif; ?>

    <?php if ($success): ?>
        <script>
            Toast.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?php echo $success; ?>'
            });
        </script>
    <?php endif; ?>

    <div class="login-container" style="max-width: 550px;">
        <div class="login-box" style="display: block; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: var(--shadow-xl); border-radius: var(--radius-lg);">
            <div class="login-form-side" style="padding: 1.75rem 2.25rem; background: transparent;">
                <div class="login-header" style="margin-bottom: 1rem; text-align: center;">
                    <div style="width: 42px; height: 42px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.6rem; font-size: 1.1rem; box-shadow: 0 4px 8px rgba(99, 102, 241, 0.3);">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; margin: 0;">Create Account</h1>
                </div>

                <form action="register.php" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="full_name" style="font-size: 0.8rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.35rem; display: block;">Full Name</label>
                        <div class="input-icon">
                            <i class="fas fa-id-card" style="color: var(--primary); font-size: 0.8rem;"></i>
                            <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required style="width: 100%; padding: 0.65rem 1rem 0.65rem 2.5rem; border: 2px solid #cbd5e1; border-radius: 8px; transition: var(--transition); font-size: 0.9rem; background: #ffffff; color: var(--text-primary);">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username" style="font-size: 0.8rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.35rem; display: block;">Username</label>
                        <div class="input-icon">
                            <i class="fas fa-user" style="color: var(--primary); font-size: 0.8rem;"></i>
                            <input type="text" id="username" name="username" placeholder="johndoe" required style="width: 100%; padding: 0.65rem 1rem 0.65rem 2.5rem; border: 2px solid #cbd5e1; border-radius: 8px; transition: var(--transition); font-size: 0.9rem; background: #ffffff; color: var(--text-primary);">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone" style="font-size: 0.8rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.35rem; display: block;">Phone Number</label>
                        <div class="input-icon">
                            <i class="fas fa-phone" style="color: var(--primary); font-size: 0.8rem;"></i>
                            <input type="tel" id="phone" name="phone" placeholder="9876543210" required style="width: 100%; padding: 0.65rem 1rem 0.65rem 2.5rem; border: 2px solid #cbd5e1; border-radius: 8px; transition: var(--transition); font-size: 0.9rem; background: #ffffff; color: var(--text-primary);">
                        </div>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label for="email" style="font-size: 0.8rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.35rem; display: block;">Email Address</label>
                        <div class="input-icon">
                            <i class="fas fa-envelope" style="color: var(--primary); font-size: 0.8rem;"></i>
                            <input type="email" id="email" name="email" placeholder="john@example.com" required style="width: 100%; padding: 0.65rem 1rem 0.65rem 2.5rem; border: 2px solid #cbd5e1; border-radius: 8px; transition: var(--transition); font-size: 0.9rem; background: #ffffff; color: var(--text-primary);">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" style="font-size: 0.8rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.35rem; display: block;">Password</label>
                        <div class="input-icon" style="position: relative;">
                            <i class="fas fa-lock" style="color: var(--primary); font-size: 0.8rem;"></i>
                            <input type="password" id="password" name="password" placeholder="••••••••" required style="width: 100%; padding: 0.65rem 2.5rem 0.65rem 2.5rem; border: 2px solid #cbd5e1; border-radius: 8px; transition: var(--transition); font-size: 0.9rem; background: #ffffff; color: var(--text-primary);">
                            <i class="fas fa-eye toggle-password" onclick="togglePass('password', this)" style="position: absolute; right: 1rem; left: auto; cursor: pointer; color: var(--text-muted); font-size: 0.8rem;"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password" style="font-size: 0.8rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.35rem; display: block;">Confirm</label>
                        <div class="input-icon" style="position: relative;">
                            <i class="fas fa-shield-alt" style="color: var(--primary); font-size: 0.8rem;"></i>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required style="width: 100%; padding: 0.65rem 2.5rem 0.65rem 2.5rem; border: 2px solid #cbd5e1; border-radius: 8px; transition: var(--transition); font-size: 0.9rem; background: #ffffff; color: var(--text-primary);">
                            <i class="fas fa-eye toggle-password" onclick="togglePass('confirm_password', this)" style="position: absolute; right: 1rem; left: auto; cursor: pointer; color: var(--text-muted); font-size: 0.8rem;"></i>
                        </div>
                    </div>

                    <div style="grid-column: span 2; margin-top: 0.5rem;">
                        <button type="submit" class="btn-login" style="width: 100%; padding: 0.85rem; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: var(--transition); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);">
                            <span>Create My Account</span>
                            <i class="fas fa-arrow-right" style="margin-left: 10px;"></i>
                        </button>
                    </div>
                </form>

                <div class="login-footer" style="margin-top: 1rem; border-top: 1px solid #f1f5f9; padding-top: 1rem; text-align: center;">
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Already have an account? <a href="login.php" style="color: var(--primary); text-decoration: none; font-weight: 700; transition: var(--transition);">Login Now</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        input:focus {
            border-color: var(--primary) !important;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
            outline: none;
        }
        .input-icon i {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            transition: var(--transition);
        }
        input:focus + i {
            color: var(--primary-dark) !important;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.4);
        }
        .login-footer a:hover {
            border-bottom-color: var(--primary);
        }
        @media (max-width: 600px) {
            form { grid-template-columns: 1fr !important; }
            .form-group { grid-column: span 1 !important; }
            .login-container { padding: 1rem; }
        }
    </style>
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
