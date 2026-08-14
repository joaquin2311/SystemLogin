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
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        body { background: #08090c; color: #8f96ab; display: flex; height: 100vh; overflow: hidden; font-size: 18px; }

        .sidebar { width: 100px; background: #0f1015; border-right: 1px solid #191b24; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 32px 0; flex-shrink: 0; }
        .logo-icon { width: 60px; height: 60px; background: #5051f9; color: #fff; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 28px; text-decoration: none; }
        .nav-icons { display: flex; flex-direction: column; gap: 32px; align-items: center; }
        .nav-item { color: #4b5066; text-decoration: none; font-size: 28px; transition: 0.2s; display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; border-radius: 16px; }
        .nav-item.active, .nav-item:hover { color: #5051f9; background: #171822; }
        .bottom-nav { display: flex; flex-direction: column; gap: 28px; align-items: center; }
        .logout-btn { color: #ef4444; text-decoration: none; font-size: 28px; }

        .wrapper { flex: 1; display: grid; grid-template-columns: 1fr 420px; grid-template-rows: 90px 1fr; height: 100vh; width: calc(100vw - 100px); }

        .top-nav { height: 90px; background: #0f1015; border-bottom: 1px solid #191b24; display: flex; justify-content: space-between; align-items: center; padding: 0 40px; grid-column: span 2; }
        .search-box { background: #151720; border: 1px solid #202330; padding: 16px 24px; border-radius: 12px; color: #fff; width: 420px; outline: none; font-size: 18px; }
        .search-box::placeholder { color: #4b5066; }
        .top-right { display: flex; align-items: center; gap: 32px; }
        .mode-toggle { font-size: 16px; background: #151720; border: 1px solid #202330; padding: 12px 22px; border-radius: 12px; color: #9da3b4; cursor: pointer; display: flex; align-items: center; gap: 10px; font-weight: 600; }
        .profile-section { display: flex; align-items: center; gap: 16px; }
        .avatar { width: 52px; height: 52px; border-radius: 50%; background: #5051f9; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 22px; }

        .content-area { padding: 40px; overflow-y: auto; display: flex; flex-direction: column; gap: 32px; }
        
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
        .title-group { display: flex; align-items: center; gap: 18px; }
        .title-group h2 { color: #fff; font-size: 36px; font-weight: 800; }
        .admin-avatars { display: flex; }
        .admin-avatars span { width: 38px; height: 38px; border-radius: 50%; border: 3px solid #08090c; background: #373a4d; display: inline-block; margin-left: -12px; }

        .stats-summary { display: flex; gap: 28px; font-size: 17px; flex-wrap: wrap; }
        .stat-item { color: #5a6075; font-weight: 600; }
        .stat-item span { color: #fff; font-weight: 800; margin-left: 6px; }
        .stat-item span.danger { color: #ef4444; }

        .filter-bar { display: flex; justify-content: space-between; align-items: center; }
        .filter-group { display: flex; gap: 14px; }
        .filter-select { background: #151720; border: 1px solid #202330; color: #9da3b4; padding: 14px 24px; border-radius: 12px; font-size: 17px; font-weight: 700; outline: none; }
        .btn-book { background: #5051f9; color: white; border: none; padding: 14px 28px; border-radius: 12px; font-weight: 800; font-size: 17px; cursor: pointer; transition: 0.2s; }
        .btn-book:hover { background: #4344d6; }

        .pc-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .pc-card { background: #111219; border: 1px solid #1c1e2b; border-radius: 16px; padding: 28px; min-height: 200px; display: flex; flex-direction: column; justify-content: space-between; }
        .pc-card.booked { background: #0b0c10; border-color: #151720; display: flex; align-items: center; justify-content: center; text-align: center; }
        .pc-card.booked .booked-title { color: #4b5066; font-size: 24px; font-weight: 800; }
        .pc-card.booked .booked-sub { color: #2e3245; font-size: 17px; margin-top: 6px; }

        .pc-top { display: flex; justify-content: space-between; align-items: center; }
        .pc-name { font-size: 15px; font-weight: 800; color: #5051f9; background: rgba(80, 81, 249, 0.15); padding: 6px 14px; border-radius: 8px; }
        .pc-options { color: #4b5066; cursor: pointer; font-size: 22px; }
        .pc-pkg { font-size: 22px; font-weight: 800; color: #fff; margin-top: 18px; }
        .pc-user { margin-top: 18px; font-size: 18px; color: #9da3b4; font-weight: 700; }
        .pc-meta { font-size: 15px; color: #4b5066; margin-top: 4px; }

        .speed-test-card { background: #111219; border: 1px solid #1c1e2b; border-radius: 16px; padding: 32px; }
        .speed-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        .speed-header strong { color: #fff; font-size: 22px; }
        .speed-controls { display: flex; gap: 14px; }
        .speed-graph { height: 80px; width: 100%; border-bottom: 1px dashed #202330; margin-bottom: 28px; position: relative; }
        .speed-graph svg { width: 100%; height: 100%; overflow: visible; }
        
        .speed-metrics { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; text-align: center; }
        .metric-box { background: #0b0c10; padding: 20px; border-radius: 12px; border: 1px solid #171822; }
        .metric-lbl { font-size: 13px; color: #4b5066; font-weight: 800; text-transform: uppercase; }
        .metric-val { color: #fff; font-size: 22px; font-weight: 800; margin-top: 8px; }

        .right-bar { background: #0f1015; border-left: 1px solid #191b24; padding: 36px 30px; overflow-y: auto; display: flex; flex-direction: column; gap: 32px; }
        .right-title { color: #fff; font-size: 22px; font-weight: 800; margin-bottom: 18px; }
        .admin-meta { font-size: 15px; background: #14151e; border: 1px solid #1f212e; padding: 22px; border-radius: 14px; display: flex; flex-direction: column; gap: 14px; }
        .admin-meta-row { display: flex; justify-content: space-between; }
        .admin-meta-row span { color: #5a6075; font-weight: 600; }
        .admin-meta-row strong { color: #fff; font-weight: 700; }

        .tab-header { display: flex; gap: 28px; border-bottom: 1px solid #1a1c27; padding-bottom: 14px; font-size: 16px; font-weight: 800; }
        .tab-item { cursor: pointer; color: #4b5066; }
        .tab-item.active { color: #5051f9; border-bottom: 3px solid #5051f9; padding-bottom: 11px; }

        .task-list { display: flex; flex-direction: column; gap: 24px; margin-top: 24px; }
        .task-group { border-left: 4px solid #5051f9; padding-left: 16px; }
        .task-title { color: #fff; font-size: 17px; font-weight: 800; }
        .task-sub { font-size: 14px; color: #4b5066; margin-top: 4px; font-weight: 600; }
        .task-sub span { color: #ef4444; font-weight: 800; }
        .task-checklist { margin-top: 14px; display: flex; flex-direction: column; gap: 12px; font-size: 16px; }
        .check-item { display: flex; align-items: center; gap: 12px; color: #8f96ab; cursor: pointer; font-weight: 600; }
        .check-item input[type="checkbox"] { accent-color: #5051f9; width: 20px; height: 20px; cursor: pointer; }

        .btn-add-task { background: #5051f9; color: white; border: none; padding: 18px; border-radius: 12px; font-weight: 800; font-size: 16px; cursor: pointer; width: 100%; margin-top: auto; transition: 0.2s; text-transform: uppercase; letter-spacing: 0.5px; }
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
                        <strong style="color: #fff; display: block; font-size: 16px;"><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                        <span style="color: #5a6075; font-size: 13px;">Intensity Zite Admin</span>
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
                        <path d="M0,50 Q125,20 250,60 T500,40" fill="none" stroke="#10b981" stroke-width="3"/>
                        <path d="M0,70 Q125,40 250,80 T500,30" fill="none" stroke="#5051f9" stroke-width="3"/>
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
                        <div class="metric-val" style="font-size: 16px;">Telkom Server</div>
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