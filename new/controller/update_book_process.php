<?php
session_start();
require_once '../controller/db/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] === 'author') {
    
    // --- 1. GET DATA FROM THE FORM ---
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $genre = $_POST['genre'];
    $author_id = $_SESSION['user_id'];
    $status = $_POST['status']; // Get the new status
    
    // --- 2. FETCH CURRENT BOOK DATA ---
    $stmt = $pdo->prepare("SELECT cover_image, status FROM books WHERE book_id = ? AND author_id = ?");
$stmt->execute([$book_id, $author_id]);
$currentBook = $stmt->fetch();
$old_status = $currentBook['status'];

    if (!$currentBook) {
        header("Location: ../view/my_books.php?error=unauthorized");
        exit();
    }
    $coverImagePath = $currentBook['cover_image'];

    // --- 3. HANDLE NEW FILE UPLOAD ---
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $targetDir = "../uploads/";
        $fileName = uniqid() . '-' . basename($_FILES["cover_image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $targetFilePath)) {
            $coverImagePath = $targetFilePath;
        }
    }

    // --- 4. UPDATE THE DATABASE ---
    try {
    // Check if the status is changing from 'Draft' to 'Published'
    if ($old_status === 'Draft' && $status === 'Published') {
        // If so, update everything AND set the completed_at date to today
        $sql = "UPDATE books 
                SET title = ?, description = ?, genre = ?, cover_image = ?, status = ?, completed_at = CURDATE() 
                WHERE book_id = ? AND author_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $description, $genre, $coverImagePath, $status, $book_id, $author_id]);
    } else {
        // Otherwise, just do a normal update without touching the completed_at date
        $sql = "UPDATE books 
                SET title = ?, description = ?, genre = ?, cover_image = ?, status = ? 
                WHERE book_id = ? AND author_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $description, $genre, $coverImagePath, $status, $book_id, $author_id]);
    }
    
    // After the update is done, redirect with a single, clear success message
    header("Location: ../view/my_books.php?status=updated");
    exit();

} catch (PDOException $e) {
    header("Location: ../view/my_books.php?error=dberror");
    exit();
}
} else {
    header("Location: ../view/my_books.php?error=unauthorized");
    exit();
}
?>