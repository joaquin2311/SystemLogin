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
    <title>Intensity Zite Internet Cafe | Admin Dashboard</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background: #0c0d12; color: #7f8599; display: flex; height: 100vh; overflow: hidden; font-size: 13px; }

        .sidebar { width: 72px; background: #08090c; border-right: 1px solid #14161f; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px 0; flex-shrink: 0; }
        .logo-icon { width: 38px; height: 38px; background: #ffffff; color: #000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 18px; text-decoration: none; }
        .nav-icons { display: flex; flex-direction: column; gap: 20px; align-items: center; }
        .nav-item { color: #434857; text-decoration: none; font-size: 18px; transition: 0.2s; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 10px; }
        .nav-item.active, .nav-item:hover { color: #5850ec; background: #12131a; }
        .bottom-nav { display: flex; flex-direction: column; gap: 16px; align-items: center; }
        .logout-btn { color: #ef4444; text-decoration: none; font-size: 18px; }

        .wrapper { flex: 1; display: grid; grid-template-columns: 1fr 340px; grid-template-rows: 64px 1fr; height: 100vh; width: calc(100vw - 72px); }

        .top-nav { height: 64px; background: #0c0d12; border-bottom: 1px solid #14161f; display: flex; justify-content: space-between; align-items: center; padding: 0 28px; grid-column: span 2; }
        .search-box { background: #12141c; border: 1px solid #1c1f2b; padding: 9px 16px; border-radius: 8px; color: #fff; width: 280px; outline: none; font-size: 13px; }
        .search-box::placeholder { color: #434857; }
        .top-right { display: flex; align-items: center; gap: 20px; }
        .mode-toggle { font-size: 12px; background: #12141c; border: 1px solid #1c1f2b; padding: 7px 14px; border-radius: 8px; color: #8e95a8; cursor: pointer; display: flex; align-items: center; gap: 8px; font-weight: 500; }
        .profile-section { display: flex; align-items: center; gap: 12px; }
        .avatar { width: 36px; height: 36px; border-radius: 50%; background: #5850ec; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px; }

        .content-area { padding: 24px 28px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; }
        
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; }
        .title-group { display: flex; align-items: center; gap: 14px; }
        .title-group h2 { color: #fff; font-size: 20px; font-weight: 700; }
        .admin-avatars { display: flex; }
        .admin-avatars span { width: 26px; height: 26px; border-radius: 50%; border: 2px solid #0c0d12; background: #2a2d3d; display: inline-block; margin-left: -8px; }

        .stats-summary { display: flex; gap: 18px; font-size: 12px; flex-wrap: wrap; }
        .stat-item { color: #585e73; font-weight: 500; }
        .stat-item span { color: #fff; font-weight: 700; margin-left: 4px; }
        .stat-item span.danger { color: #ef4444; }

        .filter-bar { display: flex; justify-content: space-between; align-items: center; }
        .filter-group { display: flex; gap: 10px; }
        .filter-select { background: #12141c; border: 1px solid #1c1f2b; color: #8e95a8; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; outline: none; }
        .btn-book { background: #5850ec; color: white; border: none; padding: 8px 18px; border-radius: 8px; font-weight: 600; font-size: 12px; cursor: pointer; transition: 0.2s; }
        .btn-book:hover { background: #473fdb; }

        .pc-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
        .pc-card { background: #12141c; border: 1px solid #1b1d28; border-radius: 10px; padding: 16px; min-height: 125px; display: flex; flex-direction: column; justify-content: space-between; }
        .pc-card.booked { background: #0e0f15; border-color: #141620; display: flex; align-items: center; justify-content: center; text-align: center; }
        .pc-card.booked .booked-title { color: #434857; font-size: 16px; font-weight: 700; }
        .pc-card.booked .booked-sub { color: #282b38; font-size: 12px; margin-top: 2px; }

        .pc-top { display: flex; justify-content: space-between; align-items: center; }
        .pc-name { font-size: 11px; font-weight: 700; color: #5850ec; background: rgba(88, 80, 236, 0.12); padding: 3px 8px; border-radius: 6px; }
        .pc-options { color: #434857; cursor: pointer; font-size: 14px; }
        .pc-pkg { font-size: 13px; font-weight: 700; color: #fff; margin-top: 10px; }
        .pc-user { margin-top: 10px; font-size: 12px; color: #8e95a8; font-weight: 600; }
        .pc-meta { font-size: 11px; color: #434857; margin-top: 2px; }

        .speed-test-card { background: #12141c; border: 1px solid #1b1d28; border-radius: 10px; padding: 20px; }
        .speed-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .speed-header strong { color: #fff; font-size: 14px; }
        .speed-controls { display: flex; gap: 10px; }
        .speed-graph { height: 50px; width: 100%; border-bottom: 1px dashed #1c1f2b; margin-bottom: 16px; position: relative; }
        .speed-graph svg { width: 100%; height: 100%; overflow: visible; }
        
        .speed-metrics { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; text-align: center; }
        .metric-box { background: #0e0f15; padding: 12px; border-radius: 8px; border: 1px solid #161822; }
        .metric-lbl { font-size: 10px; color: #434857; font-weight: 700; text-transform: uppercase; }
        .metric-val { color: #fff; font-size: 15px; font-weight: 700; margin-top: 4px; }

        .right-bar { background: #08090c; border-left: 1px solid #14161f; padding: 24px 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 24px; }
        .right-title { color: #fff; font-size: 14px; font-weight: 700; margin-bottom: 12px; }
        .admin-meta { font-size: 12px; background: #0e0f15; border: 1px solid #161822; padding: 14px; border-radius: 8px; display: flex; flex-direction: column; gap: 8px; }
        .admin-meta-row { display: flex; justify-content: space-between; }
        .admin-meta-row span { color: #585e73; }
        .admin-meta-row strong { color: #fff; font-weight: 600; }

        .tab-header { display: flex; gap: 18px; border-bottom: 1px solid #161822; padding-bottom: 8px; font-size: 12px; font-weight: 600; }
        .tab-item { cursor: pointer; color: #434857; }
        .tab-item.active { color: #5850ec; border-bottom: 2px solid #5850ec; padding-bottom: 7px; }

        .task-list { display: flex; flex-direction: column; gap: 16px; margin-top: 16px; }
        .task-group { border-left: 3px solid #5850ec; padding-left: 12px; }
        .task-title { color: #fff; font-size: 13px; font-weight: 700; }
        .task-sub { font-size: 11px; color: #434857; margin-top: 2px; }
        .task-sub span { color: #ef4444; font-weight: 600; }
        .task-checklist { margin-top: 8px; display: flex; flex-direction: column; gap: 6px; font-size: 12px; }
        .check-item { display: flex; align-items: center; gap: 8px; color: #7f8599; cursor: pointer; }
        .check-item input[type="checkbox"] { accent-color: #5850ec; width: 14px; height: 14px; cursor: pointer; }

        .btn-add-task { background: #5850ec; color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 600; font-size: 12px; cursor: pointer; width: 100%; margin-top: auto; transition: 0.2s; }
        .btn-add-task:hover { background: #473fdb; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <a href="#" class="logo-icon">C</a>
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
                        <strong style="color: #fff; display: block; font-size: 13px;"><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                        <span style="color: #434857; font-size: 11px;">First floor admin</span>
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
                    <select class="filter-select"><option>1st floor</option></select>
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
                        <div class="pc-meta">Dota • 3hr 15min</div>
                    </div>
                </div>

                <div class="pc-card">
                    <div class="pc-top">
                        <span class="pc-name">PC 2</span>
                        <span class="pc-options">⋮</span>
                    </div>
                    <div class="pc-pkg">Regular Package</div>
                    <div>
                        <div class="pc-user">Sutris</div>
                        <div class="pc-meta">Slot Gambling • 14hr 20min</div>
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
                        <div class="pc-meta">Blender • 5hr 30min</div>
                    </div>
                </div>

                <div class="pc-card booked">
                    <div>
                        <div class="booked-title">PC 4</div>
                        <div class="booked-sub">Booked</div>
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
                        <div class="pc-meta">Figma • 2hr 37min</div>
                    </div>
                </div>

                <div class="pc-card">
                    <div class="pc-top">
                        <span class="pc-name">PC 8</span>
                        <span class="pc-options">⋮</span>
                    </div>
                    <div class="pc-pkg">Twenty Hours Package</div>
                    <div>
                        <div class="pc-user">Dwiki</div>
                        <div class="pc-meta">Twitch • 5hr 30min</div>
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
                        <path d="M0,60 Q125,20 250,50 T500,40" fill="none" stroke="#22d3ee" stroke-width="2"/>
                        <path d="M0,80 Q125,40 250,70 T500,30" fill="none" stroke="#5850ec" stroke-width="2"/>
                    </svg>
                </div>

                <div class="speed-metrics">
                    <div class="metric-box">
                        <div class="metric-lbl">Download</div>
                        <div class="metric-val" style="color: #22d3ee;">102.38Mbps</div>
                    </div>
                    <div class="metric-box">
                        <div class="metric-lbl">Upload</div>
                        <div class="metric-val" style="color: #5850ec;">48.12Mbps</div>
                    </div>
                    <div class="metric-box">
                        <div class="metric-lbl">Ping</div>
                        <div class="metric-val">24MS</div>
                    </div>
                    <div class="metric-box">
                        <div class="metric-lbl">Connection</div>
                        <div class="metric-val" style="font-size: 12px;">Telkom Server</div>
                    </div>
                </div>
            </section>
        </main>

        <aside class="right-bar">
            <div>
                <div class="right-title">Admin & Maintenances</div>
                <div class="admin-meta">
                    <div class="admin-meta-row"><span>Assigned:</span> <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></div>
                    <div class="admin-meta-row"><span>Contract:</span> <strong>Aug 9, 2022 - Jan 9, 2025</strong></div>
                    <div class="admin-meta-row"><span>Status:</span> <strong style="color: #10b981;">Active</strong></div>
                </div>
            </div>

            <div>
                <div class="tab-header">
                    <span class="tab-item active">Task 10</span>
                    <span class="tab-item">Upcoming 5</span>
                    <span class="tab-item">Files 10</span>
                </div>

                <div class="task-list">
                    <div class="task-group">
                        <div class="task-title">Monthly maintenances</div>
                        <div class="task-sub">Aug 24, 2022 • <span>2 days remaining</span></div>
                        <div class="task-checklist">
                            <label class="check-item"><input type="checkbox" checked> Check for billing app</label>
                            <label class="check-item"><input type="checkbox" checked> Check internet speed</label>
                        </div>
                    </div>

                    <div class="task-group" style="border-color: #3b82f6;">
                        <div class="task-title">Upload the monthly reports</div>
                        <div class="task-sub">Aug 21, 2022</div>
                        <div class="task-checklist">
                            <label class="check-item"><input type="checkbox" checked> Revenue report</label>
                            <label class="check-item"><input type="checkbox"> Averages monthly active players</label>
                        </div>
                    </div>

                    <div class="task-group" style="border-color: #10b981;">
                        <div class="task-title">Equipment check</div>
                        <div class="task-sub">Aug 21, 2022</div>
                        <div class="task-checklist">
                            <label class="check-item"><input type="checkbox" checked> Mouse</label>
                            <label class="check-item"><input type="checkbox" checked> Keyboards</label>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn-add-task">Add new task +</button>
        </aside>
    </div>

</body>
</html>