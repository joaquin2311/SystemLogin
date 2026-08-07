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
    <title>Dashboard - System Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: linear-gradient(135deg, #0062ff 0%, #6100ff 100%);
            color: #333;
        }

        .sidebar {
            width: 250px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 20px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 30px;
            text-align: center;
        }

        .nav-links {
            list-style: none;
        }

        .nav-links li {
            margin-bottom: 15px;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            display: block;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .nav-links a:hover, .nav-links a.active {
            background: rgba(255, 255, 255, 0.25);
        }

        .logout-btn {
            background: #ff4d4d;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s ease-in-out;
        }

        .logout-btn:hover {
            background: #e63939;
        }

        .main-content {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .header-card {
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .header-card h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header-card p {
            color: #666;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card p {
            font-size: 28px;
            font-weight: bold;
            color: #0062ff;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div>
            <h2>🔒 System Admin</h2>
            <ul class="nav-links">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="#">Users</a></li>
                <li><a href="#">Settings</a></li>
            </ul>
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="main-content">
        <div class="header-card">
            <h1>Welcome back, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
            <p>Here is an overview of your system's current status.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Users</h3>
                <p>1,248</p>
            </div>
            <div class="stat-card">
                <h3>Active Sessions</h3>
                <p>42</p>
            </div>
            <div class="stat-card">
                <h3>System Status</h3>
                <p style="color: #28a745;">Online</p>
            </div>
        </div>
    </div>

</body>
</html>