<?php
session_start();
require_once './db/database.php';

// Security: Check if it's a POST request from a logged-in author
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] === 'author') {

    // Get the title from the form and the author_id from the session
    $title = $_POST['draft_title'];
    $author_id = $_SESSION['user_id'];

    // If the title is not empty, insert it into the database
    if (!empty($title)) {
        try {
            // The 'status' column will automatically be set to 'Draft' by the database default
            $sql = "INSERT INTO books (title, author_id) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $author_id]);
            header("Location: ../view/author_dashboard.php?status=draftsaved");
        } catch (PDOException $e) {
            // Handle error silently or log it
            header("Location: ../view/author_dashboard.php?status=$e");
        }
    }else {
        header("Location: ../view/author_dashboard.php?status=empty");
        // Optionally, you could set a session variable to indicate an error
        // $_SESSION['error'] = "Title cannot be empty.";
    }
}

// In all cases, redirect the user back to their dashboard
//
exit();
?>