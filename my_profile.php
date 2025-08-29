<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username, email, first_name, last_name FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

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
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>bookSpace</h2>
            <div class="sidebar-profile-area">
                <img src="profile.jpg" alt="User Avatar" class="profile-avatar">
                <p class="welcome-text">Welcome, <strong><?php echo htmlspecialchars($user['username']); ?></strong>!</p>
            </div>
            <a href="author_dashboard.php">Dashboard</a>
            <a href="my_profile.php" class="active">My Profile</a>
            <a href="#">Messages</a>
            <a href="logout.php" class="logout">Logout</a>
        </aside>

        <main class="main-content">
            <h1>My Profile</h1>
            <p class="page-subtitle">Update your public information and account details here.</p>
            <div class="profile-form-container">
                <form action="update_profile.php" method="POST">
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
                    <button type="submit" class="btn-primary">Save Changes</button>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.has('status')) {
                const status = urlParams.get('status');

                if (status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your profile has been updated.',
                        icon: 'success',
                        confirmButtonText: 'Great!'
                    });
                } 
                else if (status === 'nochange') {
                    Swal.fire({
                        title: 'No Changes Detected',
                        text: 'You did not make any changes to your profile.',
                        icon: 'info',
                        confirmButtonText: 'Okay'
                    });
                }
            } 
            else if (urlParams.has('error')) {
                const error = urlParams.get('error');

                if (error === 'dberror') {
                     Swal.fire({
                        title: 'Oops!',
                        text: 'A database error occurred. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                }
            }
        });
    </script>
</body>
</html>