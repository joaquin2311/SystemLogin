<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body {
            display: flex;
            min-height: 100vh;
            background: #0f172a;
            color: #f8fafc;
            margin: 0;
        }
        .sidebar {
            width: 260px;
            background: rgba(30, 27, 75, 0.7);
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar-brand {
            font-size: 20px;
            font-weight: 700;
            color: #818cf8;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 40px;
        }
        .nav-link-custom {
            color: #94a3b8;
            padding: 12px 16px;
            border-radius: 12px;
            text-decoration: none;
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            background: rgba(99, 102, 241, 0.2);
            color: #818cf8;
        }
        .btn-logout {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-logout:hover { background: #ef4444; color: #fff; }

        .main-content { flex: 1; padding: 40px; }
        
        .welcome-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
        }
        .stat-card h6 { color: #94a3b8; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; }
        .stat-card h2 { font-size: 32px; font-weight: 700; margin-top: 10px; margin-bottom: 0; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div>
            <div class="sidebar-brand">🔒 System Admin</div>
            <nav>
                <a href="dashboard.php" class="nav-link-custom active">Dashboard</a>
                <a href="#" class="nav-link-custom">Users</a>
                <a href="#" class="nav-link-custom">Settings</a>
            </nav>
        </div>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>

    <div class="main-content">
        <div class="welcome-card">
            <h2 class="fw-bold">Welcome back, <?php echo htmlspecialchars($_SESSION["username"]); ?>! 👋</h2>
            <p class="text-muted mb-0">Here is an overview of your system's current status.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <h6>Total Users</h6>
                    <h2 class="text-indigo" style="color: #818cf8;">1,248</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h6>Active Sessions</h6>
                    <h2 style="color: #38bdf8;">42</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h6>System Status</h6>
                    <h2 style="color: #4ade80;">Online</h2>
                </div>
            </div>
        </div>
    </div>

</body>
</html>