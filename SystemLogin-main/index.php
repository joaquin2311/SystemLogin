<?php
session_start();

// Session Management: Inactivity Timeout (15 minutes = 900 seconds)
$timeout_duration = 900; 
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    session_start();
    $error_msg = "Session expired. Please log in again.";
}
$_SESSION['last_activity'] = time();

// Initialize failed login attempts counter
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

$error_msg = $error_msg ?? "";
$max_attempts = 5;

// Check if locked out
if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
    $remaining = ceil(($_SESSION['lockout_time'] - time()) / 60);
    $error_msg = "Your account has been locked due to multiple failed login attempts. Try again in {$remaining} min.";
}

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error_msg)) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $error_msg = "Please enter both username and password.";
    } else {
        /*
          REPLACE WITH YOUR DATABASE QUERY:
          Check credentials using password_verify()
        */
        $valid_user = "admin3";
        $valid_pass = "Hrm@2026!"; // Example strong password

        if (($username === $valid_user || $username === "admin3@cyberstation.com") && $password === $valid_pass) {
            $_SESSION['login_attempts'] = 0;
            unset($_SESSION['lockout_time']);
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['login_attempts'] += 1;
            
            if ($_SESSION['login_attempts'] >= $max_attempts) {
                $_SESSION['lockout_time'] = time() + (15 * 60); // 15-minute lock
                $error_msg = "Your account has been locked due to multiple failed login attempts.";
            } else {
                // Security guideline: Generic error message
                $error_msg = "Invalid username or password.";
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
    <title>Login - CyberStation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #0b0f19;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }
        .login-card {
            background: #111827;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 35px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
        }
        .brand-title {
            font-size: 26px;
            font-weight: 700;
            color: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .card-subtext { color: #cbd5e1 !important; font-size: 14px; }
        .form-label-custom {
            color: #94a3b8 !important;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .form-control {
            background: #1e293b !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: #ffffff !important;
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
        }
        .form-control::placeholder { color: #64748b !important; opacity: 1; }
        .form-control:focus {
            border-color: #818cf8 !important;
            box-shadow: 0 0 0 0.25rem rgba(129, 140, 248, 0.25);
        }
        .form-check-input { background-color: #1e293b; border-color: rgba(255, 255, 255, 0.2); }
        .form-check-input:checked { background-color: #6366f1; border-color: #6366f1; }
        .form-check-label { color: #cbd5e1; font-size: 13px; }
        .btn-cyber {
            background: #6366f1;
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s;
        }
        .btn-cyber:hover { background: #4f46e5; color: #ffffff; }
        .link-bright { color: #38bdf8 !important; font-weight: 600; text-decoration: none; font-size: 13px; }
        .link-bright:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <div class="brand-title mb-1">
            <i class="bi bi-controller"></i> CyberStation
        </div>
        <p class="card-subtext">Enter authorized credentials to log in</p>
    </div>

    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger py-2 text-start d-flex align-items-center gap-2 mb-3" style="font-size: 13px; background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #f87171;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div><?php echo htmlspecialchars($error_msg); ?></div>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php" autocomplete="off">
        <div class="mb-3 text-start">
            <label class="form-label form-label-custom mb-1">USERNAME OR EMAIL ADDRESS</label>
            <input type="text" name="username" class="form-control" placeholder="Enter username or email" required>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label form-label-custom mb-1">PASSWORD</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <a href="#" class="link-bright">Forgot Password?</a>
        </div>

        <button type="submit" class="btn-cyber">Login to Station</button>
    </form>

    <div class="mt-4 text-center">
        <span style="color: #94a3b8; font-size: 14px;">Don't have an account?</span> 
        <a href="register.php" class="link-bright ms-1">Create Account</a>
    </div>
</div>

</body>
</html>