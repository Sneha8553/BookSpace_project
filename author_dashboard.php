<?php
session_start();
// Redirect if not logged in or not an author
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Author Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome to the Author Dashboard, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Here you can manage your books, view your stats, and write new content.</p>
        <a href="add_book.php">Add a New Book</a>
        <br><br>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>