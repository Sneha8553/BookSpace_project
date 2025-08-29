<?php
session_start();
require_once 'database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] === 'author') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $genre = $_POST['genre'];
    $author_id = $_SESSION['user_id']; 
    $coverImagePath = null;

    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $targetDir = "uploads/"; // The folder we created
        
        $fileName = uniqid() . '-' . basename($_FILES["cover_image"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $targetFilePath)) {
            $coverImagePath = $targetFilePath;
        }
    }

    try {
        $sql = "INSERT INTO books (title, description, genre, cover_image, author_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([$title, $description, $genre, $coverImagePath, $author_id]);

        header("Location: author_dashboard.php?status=success");
        exit();

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage()); // For debugging
    }

} else {
    header("Location: login.html");
    exit();
}
?>