<?php
session_start();
include 'config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!empty($username) && !empty($email) && !empty($password)) {
        if ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $success = "Account created successfully!";
            } else {
                $error = "Email or Username already exists.";
            }
            $stmt->close();
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intensity Zite Internet Cafe | Register</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', system-ui, sans-serif; }
        body { background: #08090c; color: #fff; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .register-card { width: 100%; max-width: 520px; background: #0f1015; border: 1px solid #191b24; border-radius: 16px; padding: 45px 40px; box-shadow: 0 25px 60px rgba(0,0,0,0.6); }
        .brand { text-align: center; font-size: 26px; font-weight: 800; color: #5051f9; margin-bottom: 8px; letter-spacing: 0.5px; }
        .subtitle { text-align: center; color: #7f8599; font-size: 15px; margin-bottom: 32px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 12px; font-weight: 700; color: #5a6075; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.8px; }
        .form-input { width: 100%; background: #151720; border: 1px solid #202330; padding: 15px; border-radius: 8px; color: #fff; font-size: 15px; outline: none; transition: 0.2s; }
        .form-input:focus { border-color: #5051f9; }
        .btn-register { width: 100%; background: #5051f9; color: #fff; border: none; padding: 16px; border-radius: 8px; font-weight: 700; font-size: 14px; cursor: pointer; margin-top: 10px; transition: 0.2s; text-transform: uppercase; letter-spacing: 0.5px; }
        .btn-register:hover { background: #4344d6; }
        .footer-text { text-align: center; font-size: 15px; color: #7f8599; margin-top: 26px; }
        .footer-text a { color: #5051f9; text-decoration: none; font-weight: 700; }
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; padding: 14px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; text-align: center; }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 14px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="brand">Intensity Zite Internet Cafe</div>
        <div class="subtitle">Register a new user or member account</div>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-input" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-input" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-input" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-input" required>
            </div>
            <button type="submit" class="btn-register">Create Account</button>
        </form>

        <div class="footer-text">
            Already registered? <a href="index.php">Log In</a>
        </div>
    </div>

</body>
</html>