<?php
session_start();
$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

    if (empty($username) || empty($email) || empty($password)) {
        $error_msg = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_msg = "Passwords do not match.";
    } elseif (!preg_match($password_pattern, $password)) {
        $error_msg = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character (e.g., Hrm@2026!).";
    } else {
        $success_msg = "Account created successfully! You can now log in.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - CyberStation</title>
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
        .register-card {
            background: #111827;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 35px;
            width: 100%;
            max-width: 450px;
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
        .form-control, .form-select {
            background: #1e293b !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: #ffffff !important;
            border-radius: 10px;
            padding: 11px;
            font-size: 14px;
        }
        .form-control::placeholder { color: #64748b !important; opacity: 1; }
        .form-control:focus, .form-select:focus {
            border-color: #818cf8 !important;
            box-shadow: 0 0 0 0.25rem rgba(129, 140, 248, 0.25);
        }
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

<div class="register-card">
    <div class="text-center mb-4">
        <div class="brand-title mb-1">
            <i class="bi bi-controller"></i> CyberStation
        </div>
        <p class="card-subtext">Register a new user or member account</p>
    </div>

    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger py-2 text-start mb-3" style="font-size: 13px; background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #f87171;">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success py-2 text-start mb-3" style="font-size: 13px; background: rgba(34, 197, 94, 0.15); border-color: rgba(34, 197, 94, 0.3); color: #4ade80;">
            <i class="bi bi-check-circle-fill me-1"></i> <?php echo htmlspecialchars($success_msg); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php" autocomplete="off">
        <div class="mb-3 text-start">
            <label class="form-label form-label-custom mb-1">USERNAME</label>
            <input type="text" name="username" class="form-control" placeholder="Create username" required>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label form-label-custom mb-1">EMAIL ADDRESS</label>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label form-label-custom mb-1">PASSWORD</label>
            <input type="password" name="password" class="form-control" placeholder="Min 8 chars (e.g. Hrm@2026!)" required>
        </div>

        <div class="mb-4 text-start">
            <label class="form-label form-label-custom mb-1">CONFIRM PASSWORD</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required>
        </div>

        <button type="submit" class="btn-cyber">Create Account</button>
    </form>

    <div class="mt-4 text-center">
        <span style="color: #94a3b8; font-size: 14px;">Already registered?</span> 
        <a href="index.php" class="link-bright ms-1">Log In</a>
    </div>
</div>

</body>
</html>