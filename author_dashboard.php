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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Lora:wght@400;600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="dashboard_style.css">
</head>
<body>

    <div class="dashboard-container">

        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <h1><p class="welcome-text">Welcome</h1>
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
                    <br>
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