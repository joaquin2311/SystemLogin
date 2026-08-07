<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (!empty($username) && !empty($password)) {
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Please enter valid credentials.";
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
        
        /* High-contrast labels and text */
        .card-subtext {
            color: #cbd5e1 !important; /* Lighter subtext */
            font-size: 14px;
        }
        .form-label-custom {
            color: #94a3b8 !important; /* Bright silver label text */
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Input styling with bright text and clear placeholders */
        .form-control {
            background: #1e293b !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: #ffffff !important;
            border-radius: 10px;
            padding: 12px;
        }
        .form-control::placeholder {
            color: #64748b !important; /* Visible light-grey placeholder */
            opacity: 1;
        }
        .form-control:focus {
            border-color: #818cf8 !important;
            box-shadow: 0 0 0 0.25rem rgba(129, 140, 248, 0.25);
        }

        /* Action Buttons & Links */
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
        .btn-cyber:hover { background: #4f46e5; color: #fff; }
        
        .link-bright {
            color: #38bdf8 !important; /* Bright sky blue for readability */
            font-weight: 600;
            text-decoration: none;
        }
        .link-bright:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="login-card text-center">
    <div class="fs-2 text-primary fw-bold mb-1">
        <i class="bi bi-controller"></i> CyberStation
    </div>
    <p class="card-subtext mb-4">Enter account details to unlock station access</p>

    <?php if($error): ?>
        <div class="alert alert-danger py-2 text-start" style="font-size: 14px;"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3 text-start">
            <label class="form-label form-label-custom mb-1">USERNAME OR CUSTOMER ID</label>
            <input type="text" name="username" class="form-control" placeholder="e.g. admin or customer1" required>
        </div>
        <div class="mb-4 text-start">
            <label class="form-label form-label-custom mb-1">PASSWORD / PIN</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-cyber">Start Session / Login</button>
    </form>

    <div class="mt-4 fs-6">
        <span style="color: #94a3b8;">Need a new account?</span> 
        <a href="register.php" class="link-bright ms-1">Create Member Account</a>
    </div>
</div>

</body>
</html>