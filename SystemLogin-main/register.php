<?php
session_start();

if (file_exists('config.php')) {
    require_once 'config.php';
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if (isset($pdo) && !empty($username) && !empty($email) && !empty($password)) {
        if ($password !== $confirm) {
            $error = "Passwords do not match.";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
            $stmt->execute([$email, $username]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Username or Email is already taken.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$username, $email, $hashed_password])) {
                    $success = "Account created! You can now log in.";
                } else {
                    $error = "System error during registration.";
                }
            }
        }
    } else {
        $error = isset($pdo) ? "Please fill in all fields." : "Database connection variable (\$pdo) not found in config.php.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | INTENSITY ZITE</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Roboto, sans-serif; }
        body { background: #05070a; color: #a3adc2; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }

        .reg-card { background: rgba(13, 16, 25, 0.95); border: 1px solid #1f2942; border-top: 3px solid #9d00ff; border-radius: 8px; padding: 40px; width: 440px; box-shadow: 0 20px 50px rgba(0,0,0,0.8); }
        .reg-card h2 { color: #fff; font-size: 24px; font-weight: 900; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 1px; }
        .reg-card p { font-size: 13px; color: #5a667d; margin-bottom: 24px; }

        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 10px; font-weight: 800; color: #6c7893; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 1.5px; }
        .form-control { width: 100%; background: #080a10; border: 1px solid #1a2236; padding: 12px 14px; border-radius: 4px; color: #fff; font-size: 13px; outline: none; transition: 0.3s; }
        .form-control:focus { border-color: #9d00ff; box-shadow: 0 0 10px rgba(157,0,255,0.4); }

        .btn-glow { width: 100%; background: linear-gradient(135deg, #9d00ff, #00f3ff); color: #fff; padding: 12px; border-radius: 4px; font-weight: 800; text-transform: uppercase; font-size: 12px; border: none; cursor: pointer; letter-spacing: 1.5px; box-shadow: 0 0 15px rgba(157,0,255,0.3); margin-top: 10px; transition: 0.3s; }
        .btn-glow:hover { box-shadow: 0 0 25px rgba(157,0,255,0.6); }

        .status-msg { font-size: 12px; padding: 10px; border-radius: 4px; margin-bottom: 16px; }
        .error { background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; }
        .success { background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; }

        .footer-link { text-align: center; font-size: 12px; margin-top: 20px; color: #5a667d; }
        .footer-link a { color: #00f3ff; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>

    <div class="reg-card">
        <h2>CREATE ACCOUNT</h2>
        <p>Register to unlock VIP rates and tournament signups</p>

        <?php if (!empty($error)): ?>
            <div class="status-msg error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="status-msg success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Player Tag / Username</label>
                <input type="text" name="username" class="form-control" required placeholder="GamerTag">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="player@domain.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn-glow">REGISTER NOW</button>
        </form>

        <div class="footer-link">
            Already registered? <a href="index.php">Return to Log In</a>
        </div>
    </div>

</body>
</html>