<?php
//session_start();
require_once 'database.php';

// Security: Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch ALL the user's data we need, including the profile image
$stmt = $pdo->prepare("SELECT username, email, first_name, last_name, profile_image FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

?>
<aside class="sidebar">
        
            <h2><samp style=" position: relative;    top: 0px;    right: 12px;">bookSpace</samp><img src="logo.JPEG" alt="BookSpace Logo" class="logo"></h2>
            <div class="sidebar-profile-area">
                <img src="<?php echo htmlspecialchars($user['profile_image'] ?? 'profile.jpg'); ?>" alt="User Avatar" class="profile-avatar">
                <p class="welcome-text">Welcome, <strong><?php echo htmlspecialchars($user['username']); ?></strong>!</p>
            </div>
            <a href="author_dashboard.php" class="active">Dashboard</a>
            <a href="my_profile.php">My Profile</a>
            <a href="#">Messages</a>
            <a href="logout.php" class="logout">Logout</a>
        </aside>