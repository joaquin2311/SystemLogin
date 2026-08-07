<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Example logic (Replace with MySQL auth query)
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
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 35px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }
        .form-control {
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #f8fafc;
            border-radius: 10px;
            padding: 12px;
        }
        .form-control:focus {
            background: #1e293b;
            color: #fff;
            border-color: #6366f1;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
        }
        .btn-cyber {
            background: #6366f1;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s;
        }
        .btn-cyber:hover { background: #4f46e5; }
    </style>
</head>
<body>

<div class="login-card text-center">
    <div class="fs-2 text-primary fw-bold mb-2">
        <i class="bi bi-controller"></i> CyberStation
    </div>
    <p class="text-muted mb-4">Enter account details to unlock station access</p>

    <?php if($error): ?>
        <div class="alert alert-danger py-2 text-start" style="font-size: 14px;"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3 text-start">
            <label class="form-label text-muted small fw-semibold">USERNAME OR CUSTOMER ID</label>
            <input type="text" name="username" class="form-control" placeholder="e.g. admin or customer1" required>
        </div>
        <div class="mb-4 text-start">
            <label class="form-label text-muted small fw-semibold">PASSWORD / PIN</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-cyber">Start Session / Login</button>
    </form>

    <div class="mt-4 fs-6">
        <span class="text-muted">Need a new account?</span> 
        <a href="register.php" class="text-primary text-decoration-none fw-semibold">Create Member Account</a>
    </div>
</div>

</body>
</html>