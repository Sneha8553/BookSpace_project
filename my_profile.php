<?php
session_start();
// require_once 'database.php';

// // Security: Redirect if not logged in
// if (!isset($_SESSION['user_id'])) {
//     header("Location: index.html");
//     exit();
// }

// $user_id = $_SESSION['user_id'];

// // Fetch ALL the user's data we need, including the profile image
// $stmt = $pdo->prepare("SELECT username, email, first_name, last_name, profile_image FROM users WHERE user_id = ?");
// $stmt->execute([$user_id]);
// $user = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Lora:wght@400;600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dashboard_style.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="dashboard-container">
       
                <?php include 'sidebar.php'; ?>


        <main class="main-content">
            <h1>My Profile</h1>
            <p class="page-subtitle">Update your information and account details here.</p>
            
           <div class="row justify-content-start">
            <div class="col-6">
            <div class="profile-form-container">
                <h2>Information</h2>
                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="input-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="input-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                    </div>
                    <div class="input-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                    </div>
                    <div class="input-group">
                        <label for="profile_image">Update Profile Picture</label>
                        <input type="file" id="profile_image" name="profile_image" accept="image/png, image/jpeg">
                    </div>
                    <button type="submit" class="btn-primary">Save Profile Changes</button>
                </form>
            </div>
            </div>
            <div class="col-6">
            <!-- ===== FORM 2: CHANGE PASSWORD ===== -->
            <div class="profile-form-container">
                <h2>Change Password </h2>
                <form action="update_password.php" method="POST">
                    <div class="input-group">
                        <label for="current_password">Current Password </label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="input-group">
                        <label for="new_password">New Password </label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div class="input-group">
                        <label for="confirm_new_password">Confirm New Password </label>
                        <input type="password" id="confirm_new_password" name="confirm_new_password" required>
                    </div>
                    <button type="submit" class="btn-primary">Update Password</button>
                </form>
            </div>
            </div>
            </div>
        </main>
    </div>

    <!-- JavaScript to trigger the pop-ups -->
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
</body>
</html>