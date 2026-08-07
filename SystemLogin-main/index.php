<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "login_system";

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = trim($_POST["username"]);
    $password_input = trim($_POST["password"]);

    if (!empty($user_input) && !empty($password_input)) {
        $sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $user_input, $user_input);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password_input, $hashed_password)) {
                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $id;
                            $_SESSION["username"] = $username;

                            if (isset($_POST["remember"])) {
                                setcookie("user_login", $username, time() + (86400 * 30), "/"); // 30 days
                            } else {
                                setcookie("user_login", "", time() - 3600, "/");
                            }

                            header("Location: dashboard.php");
                            exit();
                        } else {
                            $error = "Invalid username/email or password!";
                        }
                    }
                } else {
                    $error = "Invalid username/email or password!";
                }
            } else {
                $error = "Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    } else {
        $error = "Please enter both username/email and password.";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            font-family: Arial, Helvetica, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 400px;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,.2);
        }

        .logo {
            width: 90px;
            height: 90px;
            background: #0d6efd;
            color: white;
            font-size: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .btn-login {
            width: 100%;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>

<body>

<div class="login-card">

    <div class="logo">
        🔒
    </div>

    <h2>System Login</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

        <div class="mb-3">
            <label for="username" class="form-label">Username or Email</label>
            <input 
                type="text" 
                class="form-control" 
                id="username" 
                name="username" 
                placeholder="Enter Username or Email" 
                value="<?php echo isset($_COOKIE['user_login']) ? htmlspecialchars($_COOKIE['user_login']) : ''; ?>"
                required 
                autocomplete="username">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input 
                type="password" 
                class="form-control" 
                id="password" 
                name="password" 
                placeholder="Enter Password" 
                required 
                autocomplete="current-password">
        </div>

        <div class="form-check mb-3">
            <input 
                class="form-check-input" 
                type="checkbox" 
                id="remember" 
                name="remember"
                <?php echo isset($_COOKIE['user_login']) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="remember">
                Remember Me
            </label>
        </div>

        <button type="submit" class="btn btn-primary btn-login">
            Login
        </button>

        <div class="text-center mt-3">
            <a href="forgot-password.php" class="text-decoration-none">Forgot Password?</a>
            <span class="mx-1">•</span>
            <a href="register.php" class="text-decoration-none">Create Account</a>
        </div>

    </form>

    <div class="footer">
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>