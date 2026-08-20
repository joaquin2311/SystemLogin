<?php
session_start();
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($email) && !empty($password)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Please enter a valid email address.";
        } else {
            try {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                
                if ($stmt->execute([$username, $email, $hashed_password])) {
                    $_SESSION['success_message'] = "Account created successfully! Please log in.";
                    header("Location: index.php");
                    exit();
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $message = "Username or Email is already taken. Please choose another.";
                } else {
                    $message = "Registration failed: An unexpected error occurred.";
                }
            }
        }
    } else {
        $message = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Intensity Zite | Account Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">INTENSITY <span>ZITE</span></a>
    </header>

    <main style="max-width: 450px; margin: 60px auto; padding: 0 20px;">
        <div class="card-container">
            <h2>Create New Account</h2>
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 20px;">Register to unlock station bookings and tournament passes.</p>

            <?php if (!empty($message)): ?>
                <p style="color: #ff4d4d; font-size: 13px; text-align: center; margin-bottom: 10px;"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <label style="font-size: 12px; color: var(--text-muted);">Gamer Tag / Username</label>
                <input type="text" name="username" class="form-control" required placeholder="GamerTag" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">

                <label style="font-size: 12px; color: var(--text-muted);">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="player@domain.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

                <label style="font-size: 12px; color: var(--text-muted);">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">

                <button type="submit" class="btn-green">Complete Registration</button>
            </form>
        </div>
    </main>
</body>
</html>