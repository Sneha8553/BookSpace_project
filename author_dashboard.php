<?php
session_start();
require_once 'database.php'; // Your database connection

// --- SECURITY: Redirect if not logged in or not an author ---
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header("Location: index.html");
    exit();
}

// --- Get Author's Data ---
$author_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Author';

// --- Fetch Dashboard Stats ---
$stmt_books_count = $pdo->prepare("SELECT COUNT(*) FROM books WHERE author_id = ?");
$stmt_books_count->execute([$author_id]);
$total_books = $stmt_books_count->fetchColumn();

$stmt_views_count = $pdo->prepare("SELECT SUM(views) FROM books WHERE author_id = ?");
$stmt_views_count->execute([$author_id]);
$total_views = $stmt_views_count->fetchColumn() ?: 0;

// --- Fetch the Author's Published Books ---
$stmt_my_books = $pdo->prepare("SELECT book_id, title, cover_image, views, created_at FROM books WHERE author_id = ? ORDER BY created_at DESC");
$stmt_my_books->execute([$author_id]);
$my_books = $stmt_my_books->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Dashboard</title>

    <!-- THE CORRECT, COMPLETE FONT LINK -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Lora:wght@400;600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="dashboard_style.css">
</head>
<body>

    <div class="dashboard-container">

        <aside class="sidebar">
        
            <h2><samp style="
    position: relative;
    top: -31px;
    right: 12px;
">bookSpace</samp><img src="logo.JPEG" alt="BookSpace Logo" class="logo"></h2>
            <div class="sidebar-profile-area"> 
            <img src="profile.jpg" alt="User Avatar" class="profile-avatar">
             <p class="welcome-text">Welcome, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
            </div>
            <a href="author_dashboard.php" class="active">Dashboard</a>
            <a href="my_profile.php">My Profile</a>
            <a href="#">Messages</a>
            <a href="logout.php" class="logout">Logout</a>
        </aside>

        <main class="main-content">
            <h1>Author Dashboard</h1>
            <p class="page-subtitle">Your creative workspace at a glance.</p>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_books; ?></div>
                    <div class="stat-label">Total Books</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_views; ?></div>
                    <div class="stat-label">Total Views</div>
                </div>
            </div>

            <div class="my-works">
                <div class="works-header">
                    <h2>My Published Works</h2>
                    <a href="add_book.php" class="btn-primary">＋ Add New Book</a>
                </div>

                <?php if (empty($my_books)): ?>
                    <p>You haven't published any books yet. Click "Add New Book" to get started!</p>
                <?php else: ?>
                    <table class="books-table">
                    </table>
                <?php endif; ?>
            </div>
        </main>
    </div>

</body>
</html>