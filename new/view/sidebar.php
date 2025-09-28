<?php
//session_start();
require_once '../controller/db/database.php';

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
<!-- Sidebar Offcanvas for mobile -->
<div class="offcanvas offcanvas-start sidebar d-md-none" tabindex="-1" id="mobileSidebar">
    <!-- Offcanvas Header with Close Button -->
    <!-- <h2><samp style=" position: relative;    top: 0px;    right: 12px;">bookSpace</samp><img src="../logo.JPEG" alt="BookSpace Logo" class="logoaa"></h2> -->

    <div class="offcanvas-header">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
            style="position: absolute; top: 21px;right: 21px;"></button>
    </div>

    <div class="text-center mb-4">
        <img src="<?php echo htmlspecialchars($user['profile_image'] ?? 'profile.jpg'); ?>"
            class=" profile-avatar rounded-circle mb-2" alt="profile">
        <h6>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong></h6>
    </div>
    <nav class="nav flex-column">
        <a href="../view/author_dashboard.php" class="nav-link " data-bs-dismiss="offcanvas">Dashboard</a>
        <a href="my_profile.php" class="nav-link active" data-bs-dismiss="offcanvas">My Profile</a>
        <a href="#" class="nav-link" data-bs-dismiss="offcanvas">Messages</a>
        <a href="#" class="nav-link" data-bs-dismiss="offcanvas">AI Bot</a>
    </nav>
    <button class="logout-btn" onclick="logout()">LOGOUT</button>
</div>
<!-- Sidebar Fixed for Desktop -->
<nav class="col-md-3 col-lg-2 sidebar d-none d-md-block">
    <h2><samp
            style=" position: relative;    top: -3px;    right: 12px;font-family: 'Cinzel Decorative', serif;">bookSpace</samp><img
            src="../asst/img/logo.JPEG" alt="BookSpace Logo" class="logoaa"></h2>
    <div class="text-center mb-4">
        <img src="<?php echo htmlspecialchars($user['profile_image'] ?? 'profile.jpg'); ?>"
            class="profile-avatar rounded-circle mb-2" alt="profile">
        <h6>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong></h6>
    </div>
    <nav class="nav flex-column">
        <a href="../view/author_dashboard.php" class="nav-link">Dashboard</a>
        <a href="my_profile.php" class="nav-link active">My Profile</a>
        <a href="#" class="nav-link">Messages</a>
        <a href="#" class="nav-link">AI Bot</a>
    </nav>
    <button class="logout-btn" onclick="logout()">LOGOUT</button>
</nav>
<!-- Mobile Navbar with Toggle -->
<nav class="navbar navbar-light d-md-none mb-3" style=" background: #243447;">
    <div class="container-fluid">
        <button class="btn" style=" color: white;" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#mobileSidebar">
            <i class="bi bi-list" style=" font-size: 26px;"></i>
        </button>
        <h2><samp
                style="position: relative; top: 3px; right: 12px; color: white;font-family: 'Cinzel Decorative', serif;">bookSpace</samp><img
                src="../asst/img/logo.JPEG" alt="BookSpace Logo" class="logoaa"
                style="height: 30px; width: 30px; margin-top: 10px;"></h2>

    </div>
</nav>