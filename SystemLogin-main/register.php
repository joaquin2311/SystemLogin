<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "login_system";

$conn = mysqli_connect($host, $user, $password, $dbname);

$error = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password_input = $_POST["password"];

    $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

    if (empty($username) || empty($email) || empty($password_input)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!preg_match($pattern, $password_input)) {
        $error = "Password does not meet the safety requirements.";
    } else {
        $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
            if (mysqli_stmt_execute($stmt)) {
                $success = true;
            } else {
                $error = "Username or Email already exists.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
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
        .register-card {
            width: 440px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            color: #fff;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff !important;
            border-radius: 10px;
            padding: 10px 14px;
        }
        .form-control::placeholder { color: #a1a1aa; }
        .btn-custom {
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
            width: 100%;
        }
        .rules-list {
            font-size: 13px;
            list-style: none;
            padding-left: 0;
            margin-top: 8px;
        }
        .rules-list li { color: #f87171; transition: color 0.2s; }
        .rules-list li.valid { color: #4ade80; }
        a { color: #a5b4fc; text-decoration: none; }
    </style>
</head>
<body>

<div class="register-card">
    <h3 class="text-center fw-bold mb-2">Create Account</h3>
    <p class="text-center text-muted small mb-4">Join our platform today</p>

    <form action="register.php" method="POST">
        <div class="mb-3">
            <label class="form-label text-light">Username</label>
            <input type="text" name="username" class="form-control" placeholder="johndoe" required>
        </div>

        <div class="mb-3">
            <label class="form-label text-light">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label text-light">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            
            <ul class="rules-list mt-2" id="rules">
                <li id="len">✖ At least 8 characters</li>
                <li id="upper">✖ At least 1 uppercase letter</li>
                <li id="lower">✖ At least 1 lowercase letter</li>
                <li id="num">✖ At least 1 number</li>
                <li id="spec">✖ At least 1 special character (@$!%*?&)</li>
            </ul>
        </div>

        <button type="submit" class="btn btn-custom mb-3">Register</button>

        <div class="text-center text-sm">
            <span class="text-muted">Already have an account?</span> <a href="index.php">Sign In</a>
        </div>
    </form>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const rules = {
        len: { re: /.{8,}/, el: document.getElementById('len'), text: 'At least 8 characters' },
        upper: { re: /[A-Z]/, el: document.getElementById('upper'), text: 'At least 1 uppercase letter' },
        lower: { re: /[a-z]/, el: document.getElementById('lower'), text: 'At least 1 lowercase letter' },
        num: { re: /\d/, el: document.getElementById('num'), text: 'At least 1 number' },
        spec: { re: /[@$!%*?&]/, el: document.getElementById('spec'), text: 'At least 1 special character (@$!%*?&)' }
    };

    passwordInput.addEventListener('input', () => {
        const val = passwordInput.value;
        for (let key in rules) {
            const isValid = rules[key].re.test(val);
            rules[key].el.classList.toggle('valid', isValid);
            rules[key].el.textContent = (isValid ? '✔ ' : '✖ ') + rules[key].text;
        }
    });
</script>

<?php if ($success): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Account Created!',
        text: 'You can now sign in with your credentials.',
        background: '#1e1b4b',
        color: '#fff',
        confirmButtonColor: '#6366f1'
    }).then(() => { window.location.href = 'index.php'; });
</script>
<?php elseif (!empty($error)): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Registration Error',
        text: '<?php echo $error; ?>',
        background: '#1e1b4b',
        color: '#fff',
        confirmButtonColor: '#6366f1'
    });
</script>
<?php endif; ?>

</body>
</html>