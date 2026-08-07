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
                                setcookie("user_login", $username, time() + (86400 * 30), "/");
                            } else {
                                setcookie("user_login", "", time() - 3600, "/");
                            }

                            header("Location: dashboard.php");
                            exit();
                        } else {
                            $error = "Invalid credentials!";
                        }
                    }
                } else {
                    $error = "Invalid credentials!";
                }
            } else {
                $error = "Something went wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $error = "Please fill in all fields.";
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #311042 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .login-card {
            width: 420px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            color: #fff;
        }
        .logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 32px;
            margin: 0 auto 20px;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
        }
        .form-control {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff !important;
            border-radius: 10px;
            padding: 12px;
            transition: all 0.3s ease;
        }
        .form-control::placeholder { color: #a1a1aa; }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: #818cf8;
            box-shadow: 0 0 12px rgba(129, 140, 248, 0.5);
        }
        .input-group-text {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-left: none;
            color: #a5b4fc;
            cursor: pointer;
            border-radius: 0 10px 10px 0;
        }
        .password-field {
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
        .btn-custom {
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.4);
            color: #fff;
        }
        a { color: #a5b4fc; text-decoration: none; transition: color 0.2s; }
        a:hover { color: #c7d2fe; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="logo">🔒</div>
    <h3 class="text-center fw-bold mb-4">Welcome Back</h3>

    <form id="loginForm" action="index.php" method="POST">
        <div class="mb-3">
            <label class="form-label text-sm text-light">Username or Email</label>
            <input type="text" id="usernameInput" name="username" class="form-control" placeholder="Enter username or email" 
                   value="<?php echo isset($_COOKIE['user_login']) ? htmlspecialchars($_COOKIE['user_login']) : ''; ?>" required autocomplete="username">
        </div>

        <div class="mb-3">
            <label class="form-label text-sm text-light">Password</label>
            <div class="input-group">
                <input type="password" id="passwordInput" name="password" class="form-control password-field" placeholder="••••••••" required autocomplete="current-password">
                <span class="input-group-text" id="togglePasswordBtn">👁️</span>
            </div>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                   <?php echo isset($_COOKIE['user_login']) ? 'checked' : ''; ?>>
            <label class="form-check-label text-light" for="remember">Remember Me</label>
        </div>

        <button type="submit" id="submitBtn" class="btn btn-custom mb-3">Sign In</button>

        <div class="text-center text-sm">
            <a href="#">Forgot Password?</a>
            <span class="mx-2 text-muted">•</span>
            <a href="register.php">Create Account</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const loginForm = document.getElementById("loginForm");
        const passwordInput = document.getElementById("passwordInput");
        const togglePasswordBtn = document.getElementById("togglePasswordBtn");
        const submitBtn = document.getElementById("submitBtn");

        togglePasswordBtn.addEventListener("click", () => {
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";
            togglePasswordBtn.textContent = isPassword ? "🙈" : "👁️";
        });

        loginForm.addEventListener("submit", () => {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Signing in...
            `;
        });
    });
</script>

<?php if (!empty($error)): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Authentication Failed',
        text: '<?php echo $error; ?>',
        background: '#1e1b4b',
        color: '#fff',
        confirmButtonColor: '#6366f1'
    });
</script>
<?php endif; ?>

</body>
</html>