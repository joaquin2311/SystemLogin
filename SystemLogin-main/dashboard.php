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
    <title>Intensity Zite | Station Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="dashboard.php" class="logo">INTENSITY <span>ZITE</span></a>
        <div>
            <span style="margin-right: 15px; font-size: 14px;">User: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
            <a href="logout.php" style="color: #ff4d4d; text-decoration: none; font-weight: 600;">Logout</a>
        </div>
    </header>

    <main style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
        <h2>System Station Overview</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-top: 25px;">
            
            <div class="card-container" style="border-left: 4px solid var(--accent-green);">
                <h3>Station PC-01</h3>
                <p style="color: var(--text-muted); font-size: 13px;">Status: <span style="color: var(--accent-green);">Active Session</span></p>
            </div>

            <div class="card-container" style="border-left: 4px solid var(--accent-green);">
                <h3>Station PC-02</h3>
                <p style="color: var(--text-muted); font-size: 13px;">Status: <span style="color: var(--accent-green);">Active Session</span></p>
            </div>

            <div class="card-container" style="border-left: 4px solid #6b8a7a;">
                <h3>Station PC-03</h3>
                <p style="color: var(--text-muted); font-size: 13px;">Status: Available</p>
            </div>

            <div class="card-container" style="border-left: 4px solid #ff4d4d;">
                <h3>Station PC-04</h3>
                <p style="color: var(--text-muted); font-size: 13px;">Status: <span style="color: #ff4d4d;">Maintenance</span></p>
            </div>

        </div>
    </main>
</body>
</html>