<?php
session_start();
error_reporting(E_ALL & ~E_WARNING); 
ini_set('display_errors', 0);

if (file_exists('config.php')) {
    require_once 'config.php';
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (isset($pdo) && !empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect credentials submitted.";
        }
    } else {
        $error = "Please complete all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intensity Zite | Next-Gen Internet Cafe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">INTENSITY <span>ZITE</span></a>
        <nav>
            <a href="#">Rates</a>
            <a href="#">Stations</a>
            <a href="#">Tournaments</a>
            <a href="register.php">Register Account</a>
        </nav>
    </header>

    <main style="max-width: 1100px; margin: 80px auto; display: flex; gap: 60px; padding: 0 20px;">
        <div style="flex: 1;">
            <h1 style="font-size: 48px; line-height: 1.1; margin-bottom: 20px;">
                Premier gaming & high-speed workstation portal
            </h1>
            <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 30px;">
                Access high-spec PC stations equipped with ultra-low latency fiber connection, mechanical gear, and premium gaming session management.
            </p>
        </div>

        <div style="width: 380px;" class="card-container">
            <h3>Player Portal Login</h3>
            
            <?php if (!empty($error)): ?>
                <p style="color: #ff4d4d; font-size: 13px; margin: 10px 0;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form method="POST" action="index.php">
                <label style="font-size: 12px; color: var(--text-muted);">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="player@domain.com" required>

                <label style="font-size: 12px; color: var(--text-muted);">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>

                <button type="submit" class="btn-green">Start Session</button>
            </form>
        </div>
    </main>
</body>
</html>