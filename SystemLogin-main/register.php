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

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password_input = $_POST["password"];

    if (!empty($username) && !empty($email) && !empty($password_input)) {
        
        $uppercase = preg_match('@[A-Z]@', $password_input);
        $lowercase = preg_match('@[a-z]@', $password_input);
        $number    = preg_match('@[0-9]@', $password_input);
        $special   = preg_match('@[^\w]@', $password_input);

        if (!$uppercase || !$lowercase || !$number || !$special || strlen($password_input) < 8) {
            $error = "Password must meet all complexity requirements.";
        } else {
            $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
            if ($stmt = mysqli_prepare($conn, $check_sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $username, $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $error = "Username or Email already taken!";
                } else {
                    $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

                    $insert_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                    if ($insert_stmt = mysqli_prepare($conn, $insert_sql)) {
                        mysqli_stmt_bind_param($insert_stmt, "sss", $username, $email, $hashed_password);
                        if (mysqli_stmt_execute($insert_stmt)) {
                            $success = "Account created successfully! You can now log in.";
                        } else {
                            $error = "Something went wrong. Please try again.";
                        }
                        mysqli_stmt_close($insert_stmt);
                    }
                }
                mysqli_stmt_close($stmt);
            }
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
    <title>Create Account - System Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-card {
            width: 420px;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,.2);
            margin: 20px 0;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: #0d6efd;
            color: white;
            font-size: 35px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-top: 15px;
            margin-bottom: 25px;
        }

        .btn-register {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="logo">👤</div>
    <h2>Create Account</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form id="registerForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-register">Register</button>

        <div class="text-center mt-3">
            <a href="index.php" class="text-decoration-none">Already have an account? Login</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('registerForm').addEventListener('submit', function (e) {
    const password = document.getElementById('password').value;

    const minLength = password.length >= 8;
    const hasUpper = /[A-Z]/.test(password);
    const hasLower = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

    if (!minLength || !hasUpper || !hasLower || !hasNumber || !hasSpecial) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Password Format',
            html: `
                <div style="text-align: left; font-size: 14px; line-height: 1.6;">
                    <strong>Password must contain:</strong><br>
                    • At least 8 characters<br>
                    • At least 1 uppercase letter<br>
                    • At least 1 lowercase letter<br>
                    • At least 1 number<br>
                    • At least 1 special character
                </div>
            `,
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'OK'
        });
    }
});
</script>
</body>
</html>