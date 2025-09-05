<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add a New Book</title>
    <link rel="stylesheet" href="style1.css"> 
</head>
<body>
    <div class="form-container">
        <h1>Add a New Book</h1>
        <p>Fill out the details below to publish your new work.</p>

        
        <form action="add_book_process.php" method="POST" enctype="multipart/form-data">
            
            <div class="input-group">
                <input type="text" name="title" placeholder="Book Title" required>
            </div>
            
            <div class="input-group">
                <textarea name="description" placeholder="Book Description / Synopsis" rows="6"></textarea>
            </div>

            <div class="input-group">
                <input type="text" name="genre" placeholder="Genre (e.g., Fantasy, Sci-Fi)">
            </div>
            
            <div class="input-group">
                <label for="cover">Upload a Cover Image:</label>
                <input type="file" name="cover_image" id="cover" accept="image/png, image/jpeg">
            </div>

            <button type="submit" class="btn btn-primary">Publish Book</button>
            <a href="author_dashboard.php" style="display: block; text-align: center; margin-top: 15px;">Cancel</a>
        </form>
    </div>
</body>
</html>