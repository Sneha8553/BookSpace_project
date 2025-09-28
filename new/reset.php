<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Cinzel+Decorative:wght@700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #cba0e6, #a178d1);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 441px;
            padding: 30px 55px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            backdrop-filter: blur(6px);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .login-title {
            font-family: 'Cinzel Decorative', serif;
            font-size: 1.8rem;
            font-weight: bold;
            text-align: center;
            color: #2c1e4a;
        }

        .toggle-btn {
            border: none;
            border-radius: 6px;
            padding: 6px 20px;
            font-weight: 500;
            transition: 0.3s;
            flex: 1;
        }

        .toggle-btn.active {
            background-color: #4a366b;
            color: #fff;
        }

        .toggle-btn.inactive {
            background-color: #d2bce9;
            color: #4a366b;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px;
        }

        .btn-custom {
            width: 100%;
            border-radius: 10px;
            background-color: #2c1e4a;
            color: #fff;
            font-weight: 500;
            padding: 10px;
        }

        .btn-custom:hover {
            background-color: #412b66;
        }

        .extra-links {
            text-align: center;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .extra-links a {
            color: #2c1e4a;
            text-decoration: none;
            font-weight: 500;
        }

        .extra-links a:hover {
            text-decoration: underline;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #4a366b;
        }

        /* Mobile adjustments */
        @media (max-width: 576px) {
            .login-container {
                padding: 20px;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .toggle-btn {
                font-size: 0.9rem;
                padding: 6px 10px;
            }
        }

        .logo {
            border-radius: 50%;
            width: 55px;
            height: 55px;
            object-fit: cover;
            vertical-align: middle;
            margin-left: 8px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2 class="login-title mb-3">bookSpace
            <img src="./asst/img/logo.JPEG" alt="BookSpace Logo" class="logo">
        </h2>
        <p class="text-center">Your next favorite read is waiting.</p>

<?php
// reset_password.php
   require_once './controller/db/database.php';
function getTokenRow($pdo, $token) {
    $stmt = $pdo->prepare("SELECT prt.id, prt.user_id, prt.expires_at, prt.used, u.email FROM password_resets prt JOIN users u ON prt.user_id = u.user_id WHERE prt.token = ?");
    $stmt->execute([$token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = $_GET['token'] ?? '';
    if (!$token) {
        echo "Invalid token.";
        exit;
    }
    $row = getTokenRow($pdo, $token);
    if (!$row) { echo "Invalid or expired token."; exit; }
    if ($row['used']) { echo "This link has already been used."; exit; }
    if (new DateTime() > new DateTime($row['expires_at'])) { echo "Token expired."; exit; }

    // show form
    ?>
    <form method="post">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
      <input type="password" name="password" class="form-control mb-3" placeholder="New password" required>
      <input type="password" name="password_confirm" class="form-control mb-3" placeholder="Confirm password" required>
      <button type="submit"  class="btn btn-primary">Reset Password</button>
    </form>
    <?php
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (!$token || !$password || !$password_confirm) {
        ?>
    <form method="post">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
      <input type="password" name="password" class="form-control mb-3"placeholder="New password" required>
      <input type="password" name="password_confirm" class="form-control mb-3"placeholder="Confirm password" required>
      <button type="submit"  class="btn btn-primary">Reset Password</button>
    </form>
    <?php
        echo "All fields required.";
        exit;
    }
    if ($password !== $password_confirm) {
        ?>
    <form method="post">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
      <input type="password" name="password" class="form-control mb-3"placeholder="New password" required>
      <input type="password" name="password_confirm" class="form-control mb-3"placeholder="Confirm password" required>
      <button type="submit"  class="btn btn-primary">Reset Password</button>
    </form>
    <?php
        echo "Passwords do not match.";
        exit;
    }
    $row = getTokenRow($pdo, $token);
    if (!$row) { echo "Invalid or expired token."; exit; }
    if ($row['used']) { echo "Token already used."; exit; }
    if (new DateTime() > new DateTime($row['expires_at'])) { echo "Token expired."; exit; }

    // Hash password and update
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        $stmt->execute([$hash, $row['user_id']]);

        $stmt = $pdo->prepare("UPDATE password_resets SET used = 1 WHERE id = ?");
        $stmt->execute([$row['id']]);

        $pdo->commit();
        echo "Password reset successful. You can now <a href='index.php'>login</a>.";
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log($e->getMessage());
        echo "Failed to reset password.";
    }
}
?>
</div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show/Hide Password
        // const togglePassword = document.querySelector('#togglePassword');
        // const password = document.querySelector('#password');

        // togglePassword.addEventListener('click', function () {
        //     const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        //     password.setAttribute('type', type);
        //     this.classList.toggle('bi-eye');
        //     this.classList.toggle('bi-eye-slash');
        // });

        // Toggle Reader / Author
        // const readerBtn = document.querySelector('#readerBtn');
        // const authorBtn = document.querySelector('#authorBtn');
        // const selectedRoleInput = document.getElementById('selectedRole');

        // readerBtn.addEventListener('click', () => {
        //     readerBtn.classList.add('active');
        //     readerBtn.classList.remove('inactive');
        //     authorBtn.classList.remove('active');
        //     authorBtn.classList.add('inactive');
        //     // alert("Reader mode selected");
        //     selectedRoleInput.value = "reader";

        // });

        // authorBtn.addEventListener('click', () => {
        //     authorBtn.classList.add('active');
        //     authorBtn.classList.remove('inactive');
        //     readerBtn.classList.remove('active');
        //     readerBtn.classList.add('inactive');
        //     // alert("Author mode selected");
        //     selectedRoleInput.value = "author";

        // });

        // window.onload = function () {
        //     const urlParams = new URLSearchParams(window.location.search);

        //     if (urlParams.has('mailstatus')) {
        //         const errorType = urlParams.get('mailstatus');

        //         if (errorType === 'send') {
        //             alert("✅ Reset email has been sent!.");
        //         }
        //         else if (errorType === 'noexists') {
        //             alert("❌ No account found with that email.If an account with that email exists, a reset link was sent.. Please try again.");
        //         }
        //     }
        //     else if (urlParams.has('status') && urlParams.get('status') === 'success') {
        //         alert("Account created successfully! You can now sign in.");
        //     }
        // };
    </script>
</body>

</html>