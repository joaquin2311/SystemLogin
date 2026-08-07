<?php
session_start();
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $account_type = $_POST["account_type"];
    
    $success = "Account successfully registered! You can now log in.";
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
        }
        .register-card {
            background: #111827;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 35px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
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
            padding: 12px;
        }
        .form-control::placeholder { color: #64748b !important; opacity: 1; }
        
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
        
        .link-bright { color: #38bdf8 !important; font-weight: 600; text-decoration: none; }
        .link-bright:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="register-card">
    <div class="text-center mb-4">
        <div class="fs-2 text-primary fw-bold mb-1">
            <i class="bi bi-controller"></i> CyberStation
        </div>
        <p class="card-subtext">Create a Member / Staff Account</p>
    </div>

    <?php if($success): ?>
        <div class="alert alert-success py-2" style="font-size: 14px;"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label form-label-custom mb-1">USERNAME</label>
            <input type="text" name="username" class="form-control" placeholder="Choose a username" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label form-label-custom mb-1">MEMBERSHIP TIER</label>
            <select name="account_type" class="form-select">
                <option value="Standard">Regular Member ($2/hr)</option>
                <option value="VIP">VIP Gamer Tier ($3.5/hr)</option>
                <option value="Staff">Floor Staff / Admin</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label form-label-custom mb-1">PASSWORD</label>
            <input type="password" name="password" class="form-control" placeholder="Create password" required>
        </div>

        <button type="submit" class="btn-cyber">Create Account</button>
    </form>

    <div class="mt-4 text-center fs-6">
        <span style="color: #94a3b8;">Already registered?</span> 
        <a href="index.php" class="link-bright ms-1">Sign In</a>
    </div>
</div>

</body>
</html>