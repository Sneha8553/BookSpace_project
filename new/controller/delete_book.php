<?php
session_start();
require_once './db/database.php';

// Security: Check if user is a logged-in author
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header("Location: ../index.php");
    exit();
}

// Get the book ID from the URL and the author ID from the session
$book_id_to_delete = $_GET['id'] ?? null;
$author_id = $_SESSION['user_id'];

if ($book_id_to_delete) {
    try {
        // CRUCIAL SECURITY STEP:
        // Only delete the book if the book_id matches AND the author_id matches.
        // This prevents one author from deleting another author's book.
        $sql = "DELETE FROM books WHERE book_id = ? AND author_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$book_id_to_delete, $author_id]);

        // Redirect back to the book management page with a success message
        header("Location: ../view/my_books.php?status=deleted");
        exit();

    } catch (PDOException $e) {
        // Handle potential database errors
        header("Location: ../view/my_books.php?error=dberror");
        exit();
    }
} else {
    // If no book ID was provided, just go back
    header("Location: ../view/my_books.php");
    exit();
}
?>