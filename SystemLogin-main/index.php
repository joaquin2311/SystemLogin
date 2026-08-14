<?php
session_start();

// Safe config inclusion
if (file_exists('config.php')) {
    require_once 'config.php';
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

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
            $error = "Invalid email or password.";
        }
    } else {
        $error = isset($pdo) ? "Please fill in all fields." : "Database connection variable (\$pdo) not found in config.php.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INTENSITY ZITE | Cyber Gaming Arena</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Roboto, sans-serif; }
        body { background: #05070a; color: #a3adc2; overflow-x: hidden; }

        /* Neon Glow Utility Classes */
        .glow-cyan { text-shadow: 0 0 15px rgba(0, 243, 255, 0.7); color: #00f3ff; }
        .glow-purple { text-shadow: 0 0 15px rgba(157, 0, 255, 0.7); color: #9d00ff; }

        /* Header / Navbar */
        header { background: rgba(10, 12, 18, 0.95); border-bottom: 1px solid #1a2035; padding: 0 40px; height: 70px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; width: 100%; z-index: 100; backdrop-filter: blur(10px); }
        .brand-logo { font-size: 22px; font-weight: 900; letter-spacing: 2px; color: #fff; text-decoration: none; text-transform: uppercase; }
        .brand-logo span { color: #00f3ff; }
        
        nav { display: flex; gap: 30px; }
        nav a { color: #8e9bb0; text-decoration: none; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; transition: 0.3s; }
        nav a:hover { color: #00f3ff; }

        .auth-btns { display: flex; gap: 14px; align-items: center; }
        .btn-glow { background: linear-gradient(135deg, #00f3ff, #9d00ff); color: #fff; padding: 10px 22px; border-radius: 4px; font-weight: 800; text-transform: uppercase; font-size: 11px; border: none; cursor: pointer; letter-spacing: 1.5px; box-shadow: 0 0 15px rgba(0,243,255,0.3); transition: 0.3s; text-decoration: none; display: inline-block; text-align: center; }
        .btn-glow:hover { transform: translateY(-2px); box-shadow: 0 0 25px rgba(0,243,255,0.6); }

        /* Hero Container */
        .hero { min-height: 100vh; display: flex; align-items: center; justify-content: space-between; padding: 90px 8% 40px 8%; background: radial-gradient(circle at 15% 50%, rgba(157,0,255,0.18) 0%, transparent 50%), radial-gradient(circle at 85% 50%, rgba(0,243,255,0.12) 0%, transparent 50%); }
        .hero-text { max-width: 550px; }
        .hero-text h1 { font-size: 52px; font-weight: 900; color: #fff; line-height: 1.1; text-transform: uppercase; margin-bottom: 16px; letter-spacing: 1px; }
        .hero-text p { font-size: 15px; line-height: 1.6; color: #6c7893; margin-bottom: 30px; }

        /* Login Card */
        .login-card { background: rgba(13, 16, 25, 0.9); border: 1px solid #1f2942; border-top: 3px solid #00f3ff; border-radius: 8px; padding: 35px; width: 400px; box-shadow: 0 20px 50px rgba(0,0,0,0.8); }
        .login-card h3 { color: #fff; font-size: 22px; font-weight: 900; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 1px; }
        .login-card p { font-size: 12px; margin-bottom: 24px; color: #5a667d; }

        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 10px; font-weight: 800; color: #6c7893; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 1.5px; }
        .form-control { width: 100%; background: #080a10; border: 1px solid #1a2236; padding: 12px 14px; border-radius: 4px; color: #fff; font-size: 13px; outline: none; transition: 0.3s; }
        .form-control:focus { border-color: #00f3ff; box-shadow: 0 0 10px rgba(0,243,255,0.3); }

        .error-msg { background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; font-size: 12px; padding: 10px; border-radius: 4px; margin-bottom: 16px; }

        /* Feature Section */
        .esports-strip { background: #080a10; border-top: 1px solid #141a29; border-bottom: 1px solid #141a29; padding: 50px 8%; display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
        .feature-box { background: #0d1019; border: 1px solid #161d2e; padding: 28px; border-radius: 6px; }
        .feature-box h4 { font-size: 16px; font-weight: 900; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 1px; }
        .feature-box p { font-size: 13px; line-height: 1.5; color: #5a667d; }
    </style>
</head>
<body>

    <header>
        <a href="index.php" class="brand-logo">INTENSITY <span>ZITE</span></a>
        <nav>
            <a href="#rates">Rates</a>
            <a href="#games">Games</a>
            <a href="#esports">Esports Zone</a>
            <a href="#about">About Us</a>
        </nav>
        <div class="auth-btns">
            <a href="register.php" class="btn-glow">Join Arena</a>
        </div>
    </header>

    <section class="hero">
        <div class="hero-text">
            <h1 class="glow-cyan">PLAY. COMPETE. <br><span class="glow-purple">DOMINATE.</span></h1>
            <p>Experience ultra-low latency gaming with high-tier RTX stations, competitive esports stages, and 24/7 premium internet cafe amenities.</p>
            <a href="register.php" class="btn-glow">Book Station Now</a>
        </div>

        <div class="login-card">
            <h3>Player Access</h3>
            <p>Log in to access your session hours and rewards</p>

            <?php if (!empty($error)): ?>
                <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="player@domain.com">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn-glow" style="width: 100%; margin-top: 10px;">LOG IN</button>
            </form>
        </div>
    </section>

    <section class="esports-strip">
        <div class="feature-box">
            <h4 class="glow-cyan">PRO ESPORTS STAGE</h4>
            <p>240Hz Curved Displays, Mechanical Peripherals, and isolated soundproof booths for tournament play.</p>
        </div>
        <div class="feature-box">
            <h4 class="glow-purple">GIGABIT FIBER NETWORK</h4>
            <p>Dedicated dual-line fiber connection ensuring single-digit ping across regional gaming servers.</p>
        </div>
        <div class="feature-box">
            <h4 style="color: #fff;">OVERNIGHT PACKAGES</h4>
            <p>Exclusive midnight power sessions with catered food delivery directly to your PC station.</p>
        </div>
    </section>

</body>
</html>