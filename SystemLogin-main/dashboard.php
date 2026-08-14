<?php
session_start();
if (!isset($_SESSION['username'])) { 
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>intensity zite internet cafe | Admin Dashboard</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        body { background: #08090c; color: #7f8599; display: flex; height: 100vh; overflow: hidden; font-size: 13px; }

        .sidebar { width: 64px; background: #0f1015; border-right: 1px solid #191b24; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px 0; }
        .logo-icon { width: 36px; height: 36px; background: #fff; color: #000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px; text-decoration: none; }
        .nav-icons { display: flex; flex-direction: column; gap: 22px; align-items: center; }
        .nav-item { color: #434759; text-decoration: none; font-size: 18px; transition: 0.2s; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; }
        .nav-item.active, .nav-item:hover { color: #6366f1; background: #171822; }
        .bottom-nav { display: flex; flex-direction: column; gap: 18px; align-items: center; }
        .logout-btn { color: #ef4444; text-decoration: none; font-size: 18px; }

        .wrapper { flex: 1; display: grid; grid-template-columns: 1fr 320px; grid-template-rows: 55px 1fr; height: 100vh; }

        .top-nav { height: 55px; background: #0f1015; border-bottom: 1px solid #191b24; display: flex; justify-content: space-between; align-items: center; padding: 0 24px; grid-column: span 2; }
        .search-box { background: #151720; border: 1px solid #202330; padding: 7px 14px; border-radius: 6px; color: #fff; width: 260px; outline: none; font-size: 12px; }
        .search-box::placeholder { color: #4b5066; }
        .top-right { display: flex; align-items: center; gap: 20px; }
        .mode-toggle { font-size: 11px; background: #151720; border: 1px solid #202330; padding: 5px 10px; border-radius: 6px; color: #9da3b4; cursor: pointer; display: flex; align-items: center; gap: 5px; }
        .profile-section { display: flex; align-items: center; gap: 10px; }
        .avatar { width: 32px; height: 32px; border-radius: 50%; background: #6366f1; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 13px; }

        .content-area { padding: 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 18px; }
        
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; }
        .title-group { display: flex; align-items: center; gap: 12px; }
        .title-group h2 { color: #fff; font-size: 18px; font-weight: 700; }
        .admin-avatars { display: flex; }
        .admin-avatars span { width: 22px; height: 22px; border-radius: 50%; border: 2px solid #08090c; background: #373a4d; display: inline-block; margin-left: -6px; }

        .stats-summary { display: flex; gap: 16px; font-size: 11px; }
        .stat-item { color: #5a6075; }
        .stat-item span { color: #fff; font-weight: 600; margin-left: 3px; }
        .stat-item span.danger { color: #ef4444; }

        .filter-bar { display: flex; justify-content: space-between; align-items: center; margin-top: 5px; }
        .filter-group { display: flex; gap: 8px; }
        .filter-select { background: #151720; border: 1px solid #202330; color: #9da3b4; padding: 6px 12px; border-radius: 6px; font-size: 11px; outline: none; }
        .btn-book { background: #5051f9; color: white; border: none; padding: 7px 16px; border-radius: 6px; font-weight: 600; font-size: 11px; cursor: pointer; }
        .btn-book:hover { background: #4344d6; }

        .pc-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
        .pc-card { background: #111219; border: 1px solid #1c1e2b; border-radius: 8px; padding: 16px; position: relative; min-height: 125px; display: flex; flex-direction: column; justify-content: space-between; }
        .pc-card.booked { background: #0b0c10; border-color: #151720; display: flex; align-items: center; justify-content: center; text-align: center; }
        .pc-card.booked .booked-title { color: #3d4154; font-size: 13px; font-weight: 600; }
        .pc-card.booked .booked-sub { color: #282a38; font-size: 11px; margin-top: 2px; }

        .pc-top { display: flex; justify-content: space-between; align-items: center; }
        .pc-name { font-size: 11px; font-weight: 700; color: #5051f9; background: rgba(80, 81, 249, 0.1); padding: 2px 8px; border-radius: 4px; }
        .pc-options { color: #3d4154; cursor: pointer; font-size: 14px; }
        .pc-pkg { font-size: 13px; font-weight: 600; color: #fff; margin-top: 10px; }
        .pc-user { margin-top: 12px; font-size: 12px; color: #9da3b4; font-weight: 500; }
        .pc-meta { font-size: 10px; color: #434759; margin-top: 2px; }

        .speed-test-card { background: #111219; border: 1px solid #1c1e2b; border-radius: 8px; padding: 18px 20px; margin-top: 5px; }
        .speed-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .speed-header strong { color: #fff; font-size: 13px; }
        .speed-controls { display: flex; gap: 8px; }
        .speed-graph { height: 45px; width: 100%; border-bottom: 1px dashed #202330; margin-bottom: 20px; position: relative; }
        .speed-graph svg { width: 100%; height: 100%; overflow: visible; }
        
        .speed-metrics { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; text-align: center; }
        .metric-box { background: #0b0c10; padding: 10px; border-radius: 6px; border: 1px solid #171822; }
        .metric-lbl { font-size: 10px; color: #434759; }
        .metric-val { color: #fff; font-size: 13px; font-weight: 700; margin-top: 3px; }

        .right-bar { background: #0f1015; border-left: 1px solid #191b24; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; }
        .right-title { color: #fff; font-size: 13px; font-weight: 700; margin-bottom: 12px; }
        .admin-meta { font-size: 11px; background: #14151e; border: 1px solid #1f212e; padding: 12px; border-radius: 8px; display: flex; flex-direction: column; gap: 6px; }
        .admin-meta-row { display: flex; justify-content: space-between; }
        .admin-meta-row span { color: #434759; }
        .admin-meta-row strong { color: #fff; }

        .tab-header { display: flex; gap: 16px; border-bottom: 1px solid #1a1c27; padding-bottom: 8px; font-size: 11px; font-weight: 600; margin-top: 5px; }
        .tab-item { cursor: pointer; color: #434759; }
        .tab-item.active { color: #5051f9; border-bottom: 2px solid #5051f9; padding-bottom: 6px; }

        .task-list { display: flex; flex-direction: column; gap: 14px; margin-top: 15px; }
        .task-group { border-left: 2px solid #5051f9; padding-left: 10px; }
        .task-title { color: #fff; font-size: 12px; font-weight: 600; }
        .task-sub { font-size: 10px; color: #434759; margin-top: 2px; }
        .task-sub span { color: #ef4444; }
        .task-checklist { margin-top: 8px; display: flex; flex-direction: column; gap: 6px; font-size: 11px; }
        .check-item { display: flex; align-items: center; gap: 8px; color: #7f8599; }
        .check-item input[type="checkbox"] { accent-color: #5051f9; }

        .btn-add-task { background: #5051f9; color: white; border: none; padding: 10px; border-radius: 6px; font-weight: 600; font-size: 11px; cursor: pointer; width: 100%; margin-top: auto; }
        .btn-add-task:hover { background: #4344d6; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <a href="#" class="logo-icon">I</a>
        <nav class="nav-icons">
            <a href="#" class="nav-item active" title="Dashboard">⊞</a>
            <a href="#" class="nav-item" title="Analytics">📊</a>
            <a href="#" class="nav-item" title="Computers">💻</a>
            <a href="#" class="nav-item" title="Users">👥</a>
        </nav>
        <div class="bottom-nav">
            <a href="#" class="nav-item" title="Settings">⚙</a>
            <a href="logout.php" class="logout-btn" title="Logout">⏻</a>
        </div>
    </aside>

    <div class="wrapper">
        <header class="top-nav">
            <input type="text" class="search-box" placeholder="Search...">
            <div class="top-right">
                <button class="mode-toggle">☀ Light mode</button>
                <div class="profile-section">
                    <div class="avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
                    <div>
                        <strong style="color: #fff; display: block; font-size: 12px;"><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                        <span style="color: #434759; font-size: 10px;">intensity zite admin</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="content-area">
            <div class="dashboard-header">
                <div class="title-group">
                    <h2>Admins</h2>
                    <div class="admin-avatars">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <div class="stats-summary">
                    <div class="stat-item">Online: <span>50 PC</span></div>
                    <div class="stat-item">Available: <span>16 PC</span></div>
                    <div class="stat-item">Booked: <span>4 PC</span></div>
                    <div class="stat-item">Disconnected: <span class="danger">1 PC</span></div>
                    <div class="stat-item">Active member: <span>2,911,211</span></div>
                </div>
            </div>

            <div class="filter-bar">
                <div class="filter-group">
                    <select class="filter-select"><option>1st Floor</option></select>
                    <select class="filter-select"><option>Sort</option></select>
                </div>
                <button class="btn-book">Book PC +</button>
            </div>

            <section class="pc-grid">
                <div class="pc-card">
                    <div class="pc-top">
                        <span class="pc-name">PC 1</span>
                        <span class="pc-options">⋮</span>
                    </div>
                    <div class="pc-pkg">Midnight Package</div>
                    <div>
                        <div class="pc-user">Windah</div>
                        <div class="pc-meta">Dota • 5hr 13min</div>
                    </div>
                </div>

                <div class="pc-card">
                    <div class="pc-top">
                        <span class="pc-name">PC 2</span>
                        <span class="pc-options">⋮</span>
                    </div>
                    <div class="pc-pkg">Regular Package</div>
                    <div>
                        <div class="pc-user">Suntis</div>
                        <div class="pc-meta">Slot Gambling • 14hr 29min</div>
                    </div>
                </div>

                <div class="pc-card booked">
                    <div>
                        <div class="booked-title">PC 3</div>
                        <div class="booked-sub">Booked</div>
                    </div>
                </div>

                <div class="pc-card">
                    <div class="pc-top">
                        <span class="pc-name">PC 4</span>
                        <span class="pc-options">⋮</span>
                    </div>
                    <div class="pc-pkg">Midnight Package</div>
                    <div>
                        <div class="pc-user">Topik</div>
                        <div class="pc-meta">Stancer • 1hr 30min</div>
                    </div>
                </div>

                <div class="pc-card">
                    <div class="pc-top">
                        <span class="pc-name">PC 6</span>
                        <span class="pc-options">⋮</span>
                    </div>
                    <div class="pc-pkg">Midnight Package</div>
                    <div>
                        <div class="pc-user">Pesulap Merah</div>
                        <div class="pc-meta">Sudoku • 14hr 20min</div>
                    </div>
                </div>

                <div class="pc-card">
                    <div class="pc-top">
                        <span class="pc-name">PC 7</span>
                        <span class="pc-options">⋮</span>
                    </div>
                    <div class="pc-pkg">Ten Hours Package</div>
                    <div>
                        <div class="pc-user">Jit Patel</div>
                        <div class="pc-meta">Figma • 2hr 07min</div>
                    </div>
                </div>
            </section>

            <section class="speed-test-card">
                <div class="speed-header">
                    <strong>Test internet speed</strong>
                    <div class="speed-controls">
                        <select class="filter-select"><option>Telkom Server</option></select>
                        <button class="btn-book">Start test</button>
                    </div>
                </div>

                <div class="speed-graph">
                    <svg preserveAspectRatio="none" viewBox="0 0 500 100">
                        <path d="M0,50 Q125,20 250,60 T500,40" fill="none" stroke="#10b981" stroke-width="2"/>
                        <path d="M0,70 Q125,40 250,80 T500,30" fill="none" stroke="#5051f9" stroke-width="2"/>
                    </svg>
                </div>

                <div class="speed-metrics">
                    <div class="metric-box">
                        <div class="metric-lbl">Download</div>
                        <div class="metric-val" style="color: #10b981;">102.38Mbps</div>
                    </div>
                    <div class="metric-box">
                        <div class="metric-lbl">Upload</div>
                        <div class="metric-val" style="color: #5051f9;">48.12Mbps</div>
                    </div>
                    <div class="metric-box">
                        <div class="metric-lbl">Ping</div>
                        <div class="metric-val">24MS</div>
                    </div>
                    <div class="metric-box">
                        <div class="metric-lbl">Connection</div>
                        <div class="metric-val" style="font-size: 11px;">Telkom Server</div>
                    </div>
                </div>
            </section>
        </main>

        <aside class="right-bar">
            <div>
                <div class="right-title">Admin & Maintenances</div>
                <div class="admin-meta">
                    <div class="admin-meta-row"><span>Assigned:</span> <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></div>
                    <div class="admin-meta-row"><span>Contract:</span> <strong>Aug 8, 2022 - Jan 9, 2025</strong></div>
                    <div class="admin-meta-row"><span>Status:</span> <strong style="color: #10b981;">Active</strong></div>
                </div>
            </div>

            <div>
                <div class="tab-header">
                    <span class="tab-item active">Task 12</span>
                    <span class="tab-item">Upcoming 5</span>
                    <span class="tab-item">Files 11</span>
                </div>

                <div class="task-list">
                    <div class="task-group">
                        <div class="task-title">Monthly maintenances</div>
                        <div class="task-sub">Aug 24, 2026 • <span>2 days remaining</span></div>
                        <div class="task-checklist">
                            <label class="check-item"><input type="checkbox" checked> Check for billing app</label>
                            <label class="check-item"><input type="checkbox"> Check internet speed</label>
                        </div>
                    </div>

                    <div class="task-group" style="border-color: #3b82f6;">
                        <div class="task-title">Upload the monthly reports</div>
                        <div class="task-sub">Aug 21, 2026</div>
                        <div class="task-checklist">
                            <label class="check-item"><input type="checkbox" checked> Revenue report</label>
                            <label class="check-item"><input type="checkbox"> Averages monthly active players</label>
                        </div>
                    </div>

                    <div class="task-group" style="border-color: #10b981;">
                        <div class="task-title">Equipment check</div>
                        <div class="task-sub">Aug 21, 2026</div>
                        <div class="task-checklist">
                            <label class="check-item"><input type="checkbox"> Mouse</label>
                            <label class="check-item"><input type="checkbox"> Keyboards</label>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn-add-task">Add new task +</button>
        </aside>
    </div>

</body>
</html>