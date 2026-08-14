<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Account not found.";
        }
        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>intensity zite internet cafe | Login</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', system-ui, sans-serif; }
        body { background: #080b0e; color: #fff; min-height: 100vh; display: flex; flex-direction: column; background-image: radial-gradient(circle at 50% 20%, #13241f 0%, #080b0e 70%); }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 18px 40px; background: rgba(8, 11, 14, 0.8); border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        .header-left { display: flex; align-items: center; gap: 30px; }
        .logo { font-weight: 800; font-size: 18px; color: #fff; text-decoration: none; display: flex; align-items: center; gap: 8px; text-transform: lowercase; }
        .nav-links { display: flex; gap: 20px; list-style: none; }
        .nav-links a { color: #8da399; text-decoration: none; font-size: 13px; font-weight: 700; text-transform: uppercase; transition: 0.2s; }
        .nav-links a:hover { color: #10b981; }
        .header-right { display: flex; align-items: center; gap: 20px; font-size: 13px; font-weight: 600; }
        .btn-signup-nav { border: 1px solid #2e4d43; padding: 6px 16px; border-radius: 4px; color: #8da399; text-decoration: none; font-size: 11px; font-weight: 700; }
        .btn-signup-nav:hover { border-color: #10b981; color: #fff; }
        .main-container { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .login-card-wrapper { width: 100%; max-width: 900px; background: #0e1614; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.05); display: flex; overflow: hidden; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6); }
        .welcome-side { flex: 1.2; padding: 60px 50px; display: flex; flex-direction: column; justify-content: center; }
        .welcome-side h1 { font-size: 32px; font-weight: 700; margin-bottom: 15px; }
        .welcome-side p { color: #8da399; font-size: 14px; margin-bottom: 8px; }
        .welcome-side a { color: #fff; font-weight: 700; text-decoration: underline; }
        .form-side { flex: 1; background: #0a0f0e; padding: 40px 35px; display: flex; flex-direction: column; justify-content: center; border-left: 1px solid rgba(255, 255, 255, 0.03); }
        .btn-facebook { background: #3b5998; color: #fff; border: none; width: 100%; padding: 12px; border-radius: 4px; font-size: 11px; font-weight: 700; letter-spacing: 1px; cursor: pointer; margin-bottom: 20px; text-transform: uppercase; }
        .divider { text-align: center; position: relative; margin-bottom: 20px; }
        .divider::before { content: ''; position: absolute; top: 50%; left: 0; width: 100%; height: 1px; background: #1c2925; }
        .divider span { position: relative; background: #0a0f0e; padding: 0 10px; color: #5c736a; font-size: 11px; font-weight: 700; }
        .form-title { text-align: center; font-size: 11px; font-weight: 700; letter-spacing: 1.5px; color: #fff; margin-bottom: 20px; text-transform: uppercase; }
        .form-group { margin-bottom: 15px; }
        .form-input { width: 100%; background: #121a18; border: 1px solid #1c2925; padding: 12px 14px; border-radius: 4px; color: #fff; font-size: 13px; outline: none; }
        .form-input:focus { border-color: #10b981; }
        .forgot-link { display: block; color: #6b8278; font-size: 11px; text-decoration: none; margin: 5px 0 20px; }
        .btn-login { background: #388e7d; color: #fff; border: none; width: 100%; padding: 12px; border-radius: 4px; font-size: 11px; font-weight: 700; letter-spacing: 1px; cursor: pointer; text-transform: uppercase; }
        .btn-login:hover { background: #2d7365; }
        .error-msg { background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; padding: 8px 12px; border-radius: 4px; font-size: 12px; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-left">
            <a href="index.php" class="logo">intensity zite internet cafe</a>
            <ul class="nav-links">
                <li><a href="#">About Us</a></li>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Forums</a></li>
                <li><a href="#">Blog</a></li>
            </ul>
        </div>
        <div class="header-right">
            <span>EN</span>
            <span>Already have an account? <a href="index.php" style="color: #fff; text-decoration: none;">Log In</a></span>
            <a href="register.php" class="btn-signup-nav">SIGN UP</a>
        </div>
    </header>

    <main class="main-container">
        <div class="login-card-wrapper">
            <div class="welcome-side">
                <h1>Hey, Welcome Back!</h1>
                <p>Good luck on your games today!</p>
                <p>No account and want to join in? <a href="register.php">Sign up here!</a></p>
            </div>
            <div class="form-side">
                <?php if (!empty($error)): ?>
                    <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <button class="btn-facebook">Login with Facebook</button>
                <div class="divider"><span>OR</span></div>
                <div class="form-title">Log in with Email</div>
                <form method="POST" action="index.php">
                    <div class="form-group">
                        <input type="email" name="email" class="form-input" placeholder="Email Address" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-input" placeholder="Password" required>
                    </div>
                    <a href="#" class="forgot-link">Forgot password?</a>
                    <button type="submit" class="btn-login">Log In</button>
                </form>
            </div>
        </div>
    </main>

</body>
</html>