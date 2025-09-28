<?php
session_start();
require_once './db/database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] === 'author') {

    // --- Retrieve Data from the Form ---
    $title = $_POST['title'];
    $description = $_POST['description'];
    $genre = $_POST['genre'];
    $author_id = $_SESSION['user_id'];
    $coverImagePath = null;
    
    // ===== CHANGE #1: Get the 'status' from the form =====
    $status = $_POST['status']; // This line is new

    // --- Handle the File Upload (This part is unchanged) ---
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $targetDir = "../uploads/";
        
        $fileName = uniqid() . '-' . basename($_FILES["cover_image"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $targetFilePath)) {
            $coverImagePath = $targetFilePath;
        }
    }

    // --- Insert the Book into the Database ---
    try {
        // ===== CHANGE #2: Update the SQL query to include the 'status' column =====
        // We added 'status' to the list of columns and a '?' placeholder for its value.
        $sql = "INSERT INTO books (title, description, genre, cover_image, author_id, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        // Add the new '$status' variable to the end of the array being executed
        $stmt->execute([$title, 'description', $genre, $coverImagePath, $author_id, $status]);

        // Redirect back to the dashboard
        header("Location: ../view/my_books.php?status=added");
        exit();

    } catch (PDOException $e) {
        die("Database error: ". $e->getMessage()); // For debugging
    }

} else {
    // If accessed directly, redirect away
    header("Location: ../index.php"); // Changed to index.html
    exit();
}
?>