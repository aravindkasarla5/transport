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
    $phone = $_POST['phone'];

    $stmt = $pdo->prepare("SELECT id FROM users WHERE phone = ?");
    $stmt->execute([$phone]);
    $user = $stmt->fetch();

    if ($user) {
        $otp = rand(100000, 999999);
        $_SESSION['reset_user_id'] = $user['id'];
        $_SESSION['reset_otp'] = $otp;
        $_SESSION['reset_phone'] = $phone;
        header("Location: verify-otp.php?sent=1");
        exit();
    } else {
        $error = "No account found with this phone number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - CTMS</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-box" style="display: block; max-width: 450px; margin: 0 auto;">
            <div class="login-form-side" style="padding: 3rem;">
                <div class="login-header">
                    <div class="logo">
                        <i class="fas fa-lock-open"></i>
                    </div>
                    <h1>Reset Password</h1>
                    <p>Enter your phone number to receive a verification code.</p>
                </div>
                
                <?php if ($error): ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '<?php echo $error; ?>'
                        });
                    </script>
                <?php endif; ?>

                <form action="forgot-password.php" method="POST">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <div class="input-icon">
                            <i class="fas fa-mobile-alt"></i>
                            <input type="tel" id="phone" name="phone" placeholder="Enter phone number" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" style="margin-top: 1rem;">
                        <span>Get Reset Link</span>
                        <i class="fas fa-paper-plane" style="margin-left: 10px;"></i>
                    </button>
                </form>

                <div class="login-footer">
                    <p><a href="login.php" style="display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-arrow-left"></i> Back to Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
