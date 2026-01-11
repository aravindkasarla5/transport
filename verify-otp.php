<?php
require_once 'config/db.php';

if (!isset($_SESSION['reset_user_id'])) {
    header("Location: forgot-password.php");
    exit();
}

$error = '';
$success = '';
$otp_sent = $_SESSION['reset_otp'] ?? ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['verify_otp'])) {
        $entered_otp = $_POST['otp'];
        if ($entered_otp == $_SESSION['reset_otp']) {
            $_SESSION['otp_verified'] = true;
            $success = "OTP Verified! Please set your new password.";
        } else {
            $error = "Invalid OTP. Please try again.";
        }
    }

    if (isset($_POST['reset_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($stmt->execute([$hashed_password, $_SESSION['reset_user_id']])) {
                session_unset();
                session_destroy();
                header("Location: login.php?reset=success");
                exit();
            } else {
                $error = "Failed to update password.";
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
    <title>Verify OTP - CTMS</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-box" style="display: block; max-width: 480px; margin: 0 auto;">
            <div class="login-form-side" style="padding: 3rem;">
                <div class="login-header">
                    <div class="logo">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h1>Verification</h1>
                    <p>Enter the 6-digit code sent to your phone</p>
                </div>
                
                <!-- Demo Helper Alert -->
                <div style="background: rgba(99, 102, 241, 0.1); color: var(--primary-dark); padding: 1rem; border-radius: var(--radius); border-left: 4px solid var(--primary); margin-bottom: 2rem; font-size: 0.9rem;">
                    <i class="fas fa-info-circle"></i> <strong>DEMO MODE:</strong> Your OTP is <strong style="font-size: 1.1rem; color: var(--primary-dark);"><?php echo $otp_sent; ?></strong>
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

                <?php if ($success): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Verified',
                            text: '<?php echo $success; ?>'
                        });
                    </script>
                <?php endif; ?>

                <?php if (!isset($_SESSION['otp_verified'])): ?>
                    <form action="verify-otp.php" method="POST">
                        <div class="form-group">
                            <label for="otp" style="text-align: center; display: block;">6-Digit OTP</label>
                            <div class="input-icon">
                                <i class="fas fa-key"></i>
                                <input type="text" id="otp" name="otp" maxlength="6" placeholder="000000" required style="letter-spacing: 12px; text-align: center; font-weight: 700; font-size: 1.5rem; padding-left: 1rem;">
                            </div>
                        </div>
                        <button type="submit" name="verify_otp" class="btn-login" style="margin-top: 1rem;">
                            <span>Verify Code</span>
                            <i class="fas fa-check-circle" style="margin-left: 10px;"></i>
                        </button>
                    </form>
                <?php else: ?>
                    <form action="verify-otp.php" method="POST">
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="new_password" name="new_password" placeholder="••••••••" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <div class="input-icon">
                                <i class="fas fa-check-double"></i>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
                            </div>
                        </div>
                        <button type="submit" name="reset_password" class="btn-login" style="margin-top: 1rem;">
                            <span>Update Password</span>
                            <i class="fas fa-save" style="margin-left: 10px;"></i>
                        </button>
                    </form>
                <?php endif; ?>

                <div class="login-footer">
                    <p><a href="forgot-password.php">Resend OTP</a> | <a href="login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
