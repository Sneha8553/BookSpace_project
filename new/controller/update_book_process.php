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
    $stmt = $pdo->prepare("SELECT cover_image FROM books WHERE book_id = ? AND author_id = ?");
    $stmt->execute([$book_id, $author_id]);
    $currentBook = $stmt->fetch();

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
        // Add 'status = ?' to the SQL query
        $sql = "UPDATE books SET title = ?, description = ?, genre = ?, cover_image = ?, status = ? WHERE book_id = ? AND author_id = ?";
        $stmt = $pdo->prepare($sql);
        // Add the $status variable to the execute array
        $stmt->execute([$title, $description, $genre, $coverImagePath, $status, $book_id, $author_id]);
        if(strtolower($status) == strtolower('published')){
                    header("Location: ../view/my_books.php?status=published");

            // If the book is published, you might want to perform additional actions here
            // e.g., notify followers, update search index, etc.
        } else {       
                    header("Location: ../view/my_books.php?status=Draft");
        }
        exit();

    } catch (PDOException $e) {
        header("Location: ../view/my_books.php?error=dberror");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>