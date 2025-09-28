<!DOCTYPE html>
<html lang="en">
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

    <!-- Toggle Buttons -->
    <div class="d-flex justify-content-center mb-3">
      <button type="button" class="toggle-btn active" id="readerBtn">Reader</button>
      <button type="button" class="toggle-btn inactive ms-2" id="authorBtn">Author</button>
    </div>

    <!-- Form -->
    <form action="./controller/login_process.php" method="post">
      <div class="mb-3">
        <input type="hidden" id="selectedRole" name="role" value="reader">
        <input type="email" class="form-control" name="email" placeholder="Email Address">
      </div>
      <div class="mb-3 password-wrapper">
        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
        <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
      </div>
      <button type="submit" class="btn btn-custom">Sign In</button>
    </form>

    <!-- Links -->
    <div class="extra-links mt-3">
      <p><a href="forgotPassword.php">Forgot Password?</a></p>
      <p>Don't have an account? <a href="signup.html">Sign Up</a></p>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Show/Hide Password
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.classList.toggle('bi-eye-slash');
      this.classList.toggle('bi-eye');

    });

    // Toggle Reader / Author
    const readerBtn = document.querySelector('#readerBtn');
    const authorBtn = document.querySelector('#authorBtn');
    const selectedRoleInput = document.getElementById('selectedRole');

    readerBtn.addEventListener('click', () => {
      readerBtn.classList.add('active');
      readerBtn.classList.remove('inactive');
      authorBtn.classList.remove('active');
      authorBtn.classList.add('inactive');
      // alert("Reader mode selected");
      selectedRoleInput.value = "reader";

    });

    authorBtn.addEventListener('click', () => {
      authorBtn.classList.add('active');
      authorBtn.classList.remove('inactive');
      readerBtn.classList.remove('active');
      readerBtn.classList.add('inactive');
      // alert("Author mode selected");
      selectedRoleInput.value = "author";

    });

            window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.has('error')) {
                const errorType = urlParams.get('error');

                if (errorType === 'invalidcredentials') {
                    alert("Error: Invalid email or password. Please try again.");
                }
                else if (errorType === 'dberror') {
                    alert("An unexpected database error occurred. Please contact support.");
                }
            }
            else if (urlParams.has('status') && urlParams.get('status') === 'success') {
                alert("Account created successfully! You can now sign in.");
            }
        };
  </script>
</body>
</html>