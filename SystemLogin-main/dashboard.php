<?php
session_start();
if (!isset($_SESSION['username'])) { 
    $_SESSION['username'] = 'Prapto'; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberStation | Advanced Monitoring Dashboard</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        body { background: #0b0c10; color: #c5c6c7; display: flex; height: 100vh; overflow: hidden; }

        .sidebar { width: 70px; background: #12131a; border-right: 1px solid #1f2029; display: flex; flex-direction: column; align-items: center; padding: 20px 0; justify-content: space-between; }
        .logo-icon { width: 40px; height: 40px; background: #ffffff; color: #000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px; text-decoration: none; }
        .nav-icons { display: flex; flex-direction: column; gap: 25px; }
        .nav-item { color: #66687a; text-decoration: none; font-size: 20px; transition: 0.2s; }
        .nav-item.active, .nav-item:hover { color: #6366f1; }
        .logout-icon { color: #ef4444; text-decoration: none; font-size: 20px; }

        .top-nav { height: 60px; background: #12131a; border-bottom: 1px solid #1f2029; display: flex; justify-content: space-between; align-items: center; padding: 0 25px; grid-column: span 2; }
        .search-box { background: #1a1b26; border: 1px solid #2a2b3d; padding: 8px 16px; border-radius: 8px; color: #fff; width: 300px; outline: none; }
        .profile-section { display: flex; align-items: center; gap: 12px; }
        .avatar { width: 36px; height: 36px; border-radius: 50%; background: #6366f1; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }

        .wrapper { flex: 1; display: grid; grid-template-columns: 1fr 340px; grid-template-rows: 60px 1fr; height: 100vh; }
        .content-area { padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; }

        .floor-plan-card { background: #12131a; border: 1px solid #1f2029; border-radius: 12px; padding: 20px; position: relative; min-height: 380px; display: flex; flex-direction: column; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .card-title { color: #ffffff; font-size: 1.1rem; font-weight: 600; }
        
        .iso-map-container { flex: 1; background: #181924; border-radius: 8px; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid #232533; }
        .iso-grid { width: 80%; height: 80%; display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; transform: rotateX(55deg) rotateZ(-30deg); transform-style: preserve-3d; perspective: 1000px; }
        .iso-node { background: #232533; border: 1px solid #3b3e54; border-radius: 6px; padding: 10px; color: white; text-align: center; font-size: 12px; transform: translateZ(10px); box-shadow: -5px 5px 15px rgba(0,0,0,0.5); }
        .iso-node.active { border-color: #10b981; background: rgba(16, 185, 129, 0.15); }
        .iso-node.alert { border-color: #ef4444; background: rgba(239, 68, 68, 0.15); }
        .node-tag { font-size: 10px; padding: 2px 6px; border-radius: 3px; font-weight: bold; text-transform: uppercase; margin-top: 4px; display: inline-block; }
        .tag-active { background: #10b981; color: #000; }
        .tag-alert { background: #ef4444; color: #fff; }

        .data-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .sub-card { background: #12131a; border: 1px solid #1f2029; border-radius: 12px; padding: 18px; }
        
        .activity-list { display: flex; flex-direction: column; gap: 12px; margin-top: 10px; }
        .activity-item { display: flex; justify-content: space-between; align-items: center; background: #181924; padding: 10px 14px; border-radius: 8px; font-size: 13px; }
        .activity-item small { color: #66687a; }

        .telemetry-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #1f2029; }
        .telemetry-row:last-child { border-bottom: none; }
        .status-pill { padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .pill-live { background: rgba(16, 185, 129, 0.2); color: #10b981; }

        .right-bar { background: #12131a; border-left: 1px solid #1f2029; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; }
        .task-list { display: flex; flex-direction: column; gap: 10px; margin-top: 10px; }
        .task-item { background: #181924; border: 1px solid #232533; padding: 12px; border-radius: 8px; font-size: 12px; }
        .task-title { color: #fff; font-weight: 600; margin-bottom: 4px; }
        .task-date { color: #66687a; font-size: 11px; margin-bottom: 8px; }
        .btn-add-task { background: #6366f1; color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%; margin-top: auto; }
        .btn-add-task:hover { background: #4f46e5; }
    </style>
</head>
<body>

    <div class="sidebar">
        <a href="#" class="logo-icon">CC</a>
        <div class="nav-icons">
            <a href="#" class="nav-item active">⊞</a>
            <a href="#" class="nav-item">🖥</a>
            <a href="#" class="nav-item">👥</a>
            <a href="#" class="nav-item">⚙</a>
        </div>
        <a href="logout.php" class="logout-icon" title="Logout">⏻</a>
    </div>

    <div class="wrapper">
        <div class="top-nav">
            <input type="text" class="search-box" placeholder="Search terminals, logs, users...">
            <div class="profile-section">
                <div class="avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
                <div>
                    <strong style="color: #fff; font-size: 14px;"><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                    <div style="font-size: 11px; color: #66687a;">First Floor Admin</div>
                </div>
            </div>
        </div>

        <div class="content-area">
            <div class="floor-plan-card">
                <div class="card-header">
                    <span class="card-title">Live Floor Plan</span>
                    <span style="font-size: 12px; color: #10b981;">● Live Network View</span>
                </div>
                <div class="iso-map-container">
                    <div class="iso-grid">
                        <div class="iso-node active">
                            <div>PC-01</div>
                            <span class="node-tag tag-active">ACTIVE</span>
                        </div>
                        <div class="iso-node active">
                            <div>PC-02</div>
                            <span class="node-tag tag-active">ACTIVE</span>
                        </div>
                        <div class="iso-node alert">
                            <div>PC-03</div>
                            <span class="node-tag tag-alert">ALERT</span>
                        </div>
                        <div class="iso-node active">
                            <div>PC-04</div>
                            <span class="node-tag tag-active">ACTIVE</span>
                        </div>
                        <div class="iso-node alert">
                            <div>VIP-01</div>
                            <span class="node-tag tag-alert">ALERT</span>
                        </div>
                        <div class="iso-node active">
                            <div>VIP-02</div>
                            <span class="node-tag tag-active">ACTIVE</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="data-grid">
                <div class="sub-card">
                    <span class="card-title">Recent Customer Activity</span>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div>
                                <strong style="color:#fff;"><?php echo htmlspecialchars($_SESSION['username']); ?></strong> logged in
                            </div>
                            <small>3 mins ago</small>
                        </div>
                        <div class="activity-item">
                            <div>
                                <strong style="color:#fff;">Cesar</strong> extended PC-02 session
                            </div>
                            <small>1 hour ago</small>
                        </div>
                        <div class="activity-item">
                            <div>
                                <strong style="color:#fff;">Aaron</strong> unlocked VIP-01
                            </div>
                            <small>2 hours ago</small>
                        </div>
                    </div>
                </div>

                <div class="sub-card">
                    <span class="card-title">Real-Time Server Status</span>
                    <div style="margin-top: 10px;">
                        <div class="telemetry-row">
                            <span>Temperature</span>
                            <strong style="color: #fff;">35 °C</strong>
                        </div>
                        <div class="telemetry-row">
                            <span>Power Usage</span>
                            <strong style="color: #fff;">220 W</strong>
                        </div>
                        <div class="telemetry-row">
                            <span>Rack Status</span>
                            <span class="status-pill pill-live">LIVE</span>
                        </div>
                        <div class="telemetry-row">
                            <span>Server Latency</span>
                            <strong style="color: #10b981;">5 ms avg</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-bar">
            <div>
                <span class="card-title">Admin & Maintenances</span>
                <div style="margin-top: 10px; font-size: 12px;">
                    <div>Assigned: <strong style="color:#fff;"><?php echo htmlspecialchars($_SESSION['username']); ?></strong></div>
                    <div>Status: <span style="color:#10b981;">Active Session</span></div>
                </div>
            </div>

            <div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 10px;">
                    <strong style="color:#fff;">Tasks</strong>
                    <span style="color:#66687a;">Upcoming</span>
                </div>
                <div class="task-list">
                    <div class="task-item">
                        <div class="task-title">Monthly Maintenances</div>
                        <div class="task-date">Aug 24, 2026 • 2 days remaining</div>
                        <div style="color: #a5a6f6; font-size: 11px;">Check internet speeds & billing server</div>
                    </div>
                    <div class="task-item">
                        <div class="task-title">Upload Monthly Reports</div>
                        <div class="task-date">Aug 28, 2026</div>
                        <div style="color: #a5a6f6; font-size: 11px;">Averages for monthly active members</div>
                    </div>
                    <div class="task-item">
                        <div class="task-title">Equipment Check</div>
                        <div class="task-date">Aug 30, 2026</div>
                        <div style="color: #a5a6f6; font-size: 11px;">Check mechanical keyboards & headsets</div>
                    </div>
                </div>
            </div>

            <button class="btn-add-task">+ Add New Task</button>
        </div>
    </div>

</body>
</html>