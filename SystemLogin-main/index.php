<?php
session_start();

// Standard PHP error reporting set to suppress warnings on-screen for a small shop setting
error_reporting(E_ALL & ~E_WARNING); 
ini_set('display_errors', 0);

// Safe config inclusion and basic db connectivity check
if (file_exists('config.php')) {
    require_once 'config.php';
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect inputs safely, even if 'password' isn't set, avoiding the specific warning
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
            $error = "Incorrect Login."; // Generic shop-style error
        }
    } else {
        $error = "Login Failed."; // Avoid specific 'fill in fields' warning
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>INTENSITY ZITE | street side internet</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Courier, 'Courier New', monospace; }
        body { background: #000000; color: #5a85ab; } /* Basic dark blue text on pure black */

        /* De-styled Header */
        header { border-bottom: 2px solid #28395e; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 18px; font-weight: bold; color: #fff; text-transform: uppercase; }
        .logo span { color: #1e70e1; }
        nav { display: flex; gap: 15px; }
        nav a { color: #5a85ab; text-decoration: none; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        nav a:hover { color: #1e70e1; }
        .btn-join { background: #1e70e1; color: #fff; padding: 6px 12px; border-radius: 2px; font-size: 11px; font-weight: bold; text-decoration: none; text-transform: uppercase; border: 1px solid #fff; }

        /* Very simplified main section, lower visual density */
        main { min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px; }
        .content-container { display: flex; gap: 40px; align-items: flex-start; max-width: 900px; }
        
        .promo-text { width: 50%; padding-top: 30px; }
        .promo-text h1 { font-size: 32px; font-weight: bold; color: #fff; text-transform: uppercase; margin-bottom: 10px; }
        .promo-text p { font-size: 12px; color: #5a85ab; margin-bottom: 20px; line-height: 1.5; }
        .btn-book { background: #0c0f16; color: #1e70e1; border: 1px solid #1e70e1; padding: 8px 16px; border-radius: 2px; font-size: 11px; text-decoration: none; display: inline-block; text-transform: uppercase; }

        /* Login Card: Pure 90s PH shop aesthetic: black box, simple border */
        .login-card { background: #000; border: 2px solid #1e70e1; border-radius: 3px; padding: 25px; width: 340px; }
        .login-card h3 { color: #fff; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 10px; color: #5a85ab; text-transform: uppercase; margin-bottom: 4px; }
        .form-control { width: 100%; background: #000; border: 1px solid #28395e; padding: 10px; border-radius: 2px; color: #fff; font-size: 12px; font-family: inherit; }
        .form-control:focus { border-color: #1e70e1; outline: none; }

        /* Basic submit button */
        .btn-login { width: 100%; background: #1e70e1; color: #fff; padding: 10px; border: 1px solid #fff; border-radius: 2px; font-size: 12px; font-weight: bold; text-transform: uppercase; cursor: pointer; margin-top: 10px; }
        .btn-login:hover { background: #155cb7; }

        /* Standardized error output - no red background box */
        .error-msg { font-size: 11px; color: #ef4444; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>

    <header>
        <a href="index.php" class="logo">INTENSITY <span>ZITE</span></a>
        <nav>
            <a href="#">Rates</a>
            <a href="#">Games</a>
            <a href="#">Esports</a>
            <a href="#">About</a>
        </nav>
        <a href="register.php" class="btn-join">Join Hub</a>
    </header>

    <main>
        <div class="content-container">
            <div class="promo-text">
                <h1>PLAY. COMPETE. <span style="color: #1e70e1;">DOMINATE.</span></h1>
                <p>Play Valorant, DOTA 2, MLBB, and more on high-spec PCs. Street-side gaming hub open 24/7. High-speed internet. Mechanical keyboards.</p>
                <a href="#" class="btn-book">Book Station Now</a>
            </div>

            <div class="login-card">
                <h3>Customer Access</h3>
                
                <?php if (!empty($error)): ?>
                    <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php">
                    <div class="form-group">
                        <label>Customer Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="player@cafe.ph">
                    </div>
                    <div class="form-group">
                        <label>Session Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn-login">LOGIN TO SESSION</button>
                </form>
            </div>
        </div>
    </main>

</body>
</html>