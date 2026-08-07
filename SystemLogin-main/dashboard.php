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
    <title>CyberStation - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body {
            display: flex;
            min-height: 100vh;
            background: #0b0f19;
            color: #f8fafc;
            margin: 0;
            overflow-x: hidden;
        }

        /* Left Navigation Sidebar */
        .sidebar {
            width: 250px;
            background: #111827;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            padding: 25px 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
            position: sticky;
            top: 0;
        }
        .sidebar-brand {
            font-size: 20px;
            font-weight: 700;
            color: #6366f1;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 35px;
        }
        .nav-link-custom {
            color: #94a3b8;
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            background: rgba(99, 102, 241, 0.15);
            color: #818cf8;
        }
        .btn-logout {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 10px;
            padding: 11px;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-logout:hover { background: #ef4444; color: #fff; }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
            height: 100vh;
            overflow-y: auto;
        }
        .welcome-card {
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 25px;
        }
        .welcome-subtext {
            color: #f1f5f9 !important; 
            font-size: 15px;
            margin-top: 6px;
            margin-bottom: 0;
            opacity: 0.95;
        }

        .stat-card {
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 22px;
            text-align: center;
        }
        .stat-card h6 { color: #94a3b8; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; }
        .stat-card h2 { font-size: 32px; font-weight: 700; margin-top: 10px; margin-bottom: 0; }

        /* PC Station Grid */
        .pc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .pc-card {
            background: #1e293b;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
        }
        .pc-card.occupied { border-color: #ef4444; }
        .pc-card.available { border-color: #22c55e; }
        .pc-card.vip { border-color: #a855f7; }
        .pc-status-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .status-occupied { background: rgba(239, 68, 68, 0.2); color: #f87171; }
        .status-available { background: rgba(34, 197, 94, 0.2); color: #4ade80; }

        /* Right Panel Feed */
        .right-panel {
            width: 320px;
            background: #111827;
            border-left: 1px solid rgba(255, 255, 255, 0.08);
            padding: 25px 20px;
            height: 100vh;
            overflow-y: auto;
            position: sticky;
            top: 0;
        }
        .panel-title {
            font-size: 16px;
            font-weight: 600;
            color: #f8fafc;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .feed-item {
            background: #1e293b;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .feed-user {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }
        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <!-- Left Sidebar -->
    <div class="sidebar">
        <div>
            <div class="sidebar-brand">
                <i class="bi bi-controller"></i> CyberStation
            </div>
            <nav>
                <a href="dashboard.php" class="nav-link-custom active"><i class="bi bi-pc-display"></i> Stations</a>
                <a href="#" class="nav-link-custom"><i class="bi bi-people-fill"></i> Customers</a>
                <a href="#" class="nav-link-custom"><i class="bi bi-clock-history"></i> Session Logs</a>
                <a href="#" class="nav-link-custom"><i class="bi bi-gear-fill"></i> System Config</a>
            </nav>
        </div>
        <a href="logout.php" class="btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="welcome-card">
            <h2 class="fw-bold mb-0">CyberStation Management 👋</h2>
            <p class="welcome-subtext">Logged in as: <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong> | Live network status.</p>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <h6>Active Computers</h6>
                    <h2 style="color: #818cf8;">28 / 40</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h6>Available Stations</h6>
                    <h2 style="color: #38bdf8;">12</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h6>Server Status</h6>
                    <h2 style="color: #4ade80;">Online</h2>
                </div>
            </div>
        </div>

        <!-- Live Station Grid -->
        <h5 class="fw-bold mb-3"><i class="bi bi-cpu me-2"></i>Live Floor Plan</h5>
        <div class="pc-grid">
            <div class="pc-card occupied">
                <i class="bi bi-pc-display fs-3 text-danger"></i>
                <div class="fw-bold mt-1">PC-01</div>
                <span class="pc-status-badge status-occupied">Occupied</span>
            </div>
            <div class="pc-card occupied">
                <i class="bi bi-pc-display fs-3 text-danger"></i>
                <div class="fw-bold mt-1">PC-02</div>
                <span class="pc-status-badge status-occupied">Occupied</span>
            </div>
            <div class="pc-card available">
                <i class="bi bi-pc-display fs-3 text-success"></i>
                <div class="fw-bold mt-1">PC-03</div>
                <span class="pc-status-badge status-available">Ready</span>
            </div>
            <div class="pc-card occupied vip">
                <i class="bi bi-controller fs-3 text-warning"></i>
                <div class="fw-bold mt-1">VIP-01</div>
                <span class="pc-status-badge status-occupied">VIP Active</span>
            </div>
            <div class="pc-card available">
                <i class="bi bi-pc-display fs-3 text-success"></i>
                <div class="fw-bold mt-1">PC-04</div>
                <span class="pc-status-badge status-available">Ready</span>
            </div>
        </div>
    </div>

    <!-- Right Side Feed -->
    <div class="right-panel">
        <div class="panel-title">
            <span>Recent Activity</span>
            <i class="bi bi-three-dots text-muted"></i>
        </div>

        <div class="feed-item">
            <div class="feed-user">
                <div class="avatar">C</div>
                <div>
                    <div style="font-size: 13px; font-weight: 600;">Cesar</div>
                    <div style="font-size: 11px; color: #94a3b8;">System Admin</div>
                </div>
            </div>
            <p style="font-size: 13px; color: #cbd5e1; margin: 0;">Added 3 hours pre-paid time to PC-02 session.</p>
        </div>

        <div class="feed-item">
            <div class="feed-user">
                <div class="avatar" style="background: #a855f7;">A</div>
                <div>
                    <div style="font-size: 13px; font-weight: 600;">Aaron</div>
                    <div style="font-size: 11px; color: #94a3b8;">Floor Staff</div>
                </div>
            </div>
            <p style="font-size: 13px; color: #cbd5e1; margin: 0;">Unlocked VIP-01 station for tournament practice.</p>
        </div>
    </div>

</body>
</html>