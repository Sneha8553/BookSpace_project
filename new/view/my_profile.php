<?php
session_start();
require_once '../controller/db/database.php';

$username = $_SESSION['username'];
// // Security: Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch ALL the user's data we need, including the profile image
$stmt = $pdo->prepare("SELECT username, email, first_name, last_name, profile_image FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookSpace Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Lora:wght@400;600&family=Poppins:wght@400;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5.0.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f6ecf9;
        }

        .sidebar {
            background-color: #243447;
            min-height: 100vh;
            padding: 20px;
            color: #fff;
        }

        .sidebar .nav-link {
            color: #fff;
            margin: 10px 0;
            border-radius: 6px;
            transition: 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #1c2e3f;
        }

        .logout-btn {
            width: 100%;
            background-color: #d9534f;
            border: none;
            padding: 10px;
            color: #fff;
            font-weight: 600;
            border-radius: 6px;
            margin-top: 20px;
        }

        .main-content {
            padding: 20px;
        }

        .welcome-text h2 {
            font-weight: 700;
            color: #2c1e4a;
        }

        .card-custom {
            border-radius: 10px;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .card-blue {
            background: #0066ff;
        }

        .card-teal {
            background: #008080;
        }

        .draft-box {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .draft-box input {
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .save-btn {
            width: 100%;
            background-color: #6c757d;
            color: #fff;
            border-radius: 6px;
            padding: 10px;
            border: none;
        }

        .add-book-btn {
            background: linear-gradient(90deg, #6a11cb, #2575fc);
            color: #fff;
            border-radius: 30px;
            padding: 10px 20px;
            border: none;
            font-weight: 600;
            /* position: absolute; */
            /* right: 20px;
      top: 10px; */
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 50%;
            border-width: 3px;
            border-style: solid;
            border-color: rgb(236, 240, 241);
            border-image: initial;
        }

        .sidebar h2 {
            font-family: "Cinzel Decorative", cursive;
            font-size: 1.5rem;
            text-align: center;
            margin: 0px 0px 20px;
        }

        .logoaa {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 50%;
            border-width: 3px;
            border-style: solid;
            border-color: rgb(236, 240, 241);
            border-image: initial;
        }

        .float-right {
            float: right;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <?php include 'sidebar.php'; ?>
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="container">
                    <div class="welcome-text mb-3">
                        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
                        <p class="text-muted">YOUR CREATIVE WORKSPACE AT A GLANCE.</p>
                    </div>

                    <!-- Cards -->
                    <main class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>My Profile</h5>
                                    <p>Update your information and account details here.</p>

                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="row justify-content-start">
                                            <div class="col-md-6 col-sm-12">
                                                <div class="profile-form-container">
                                                    <h2>Information</h2>
                                                    <form action="../controller/update_profile.php" method="POST"
                                                        enctype="multipart/form-data">
                                                        <div class="mb-3">
                                                            <label for="username" class="form-label">Username</label>
                                                            <input type="text" class="form-control" id="username" name="username"
                                                                value="<?php echo htmlspecialchars($user['username']); ?>"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">Email Address</label>
                                                            <input type="email" class="form-control" id="email" name="email"
                                                                value="<?php echo htmlspecialchars($user['email']); ?>"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="first_name" class="form-label">First Name</label>
                                                            <input type="text" class="form-control" id="first_name" name="first_name"
                                                                value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="last_name" class="form-label">Last Name</label>
                                                            <input type="text" class="form-control" id="last_name" name="last_name"
                                                                value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="profile_image" class="form-label">Update Profile Picture</label>
                                                            <input type="file" class="form-control" id="profile_image" name="profile_image"
                                                                accept="image/png, image/jpeg">
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Save Profile
                                                            Changes</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <!-- ===== FORM 2: CHANGE PASSWORD ===== -->
                                                <div class="profile-form-container">
                                                    <h2>Change Password </h2>
                                                    <form action="../controller/update_password.php" method="POST">
                                                        <div class="mb-3">
                                                            <label for="current_password" class="form-label">Current Password </label>
                                                            <input type="password" class="form-control" id="current_password"
                                                                name="current_password" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="new_password" class="form-label">New Password </label>
                                                            <input type="password" class="form-control" id="new_password" name="new_password"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="confirm_new_password" class="form-label">Confirm New Password
                                                            </label>
                                                            <input class="form-control" type="password" id="confirm_new_password"
                                                                name="confirm_new_password" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Update
                                                            Password</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>




                        </div>
                    </main>

                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const error = urlParams.get('error');

            if (status === 'success') {
                Swal.fire('Success!', 'Your profile has been updated.', 'success');
            } else if (status === 'nochange') {
                Swal.fire('No Changes', 'You did not make any changes to your profile.', 'info');
            } else if (status === 'pwdsuccess') {
                Swal.fire('Success!', 'Your password has been changed successfully.', 'success');
            } else if (error === 'wrongpwd') {
                Swal.fire('Error!', 'The current password you entered is incorrect.', 'error');
            } else if (error === 'pwdnomatch') {
                Swal.fire('Error!', 'The new passwords do not match.', 'error');
            } else if (error === 'dberror') {
                Swal.fire('Oops!', 'A database error occurred. Please try again.', 'error');
            }
        });
    </script>
        <script src="../asst/js/style.js"></script>


</html>