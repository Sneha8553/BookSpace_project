<?php
require_once '../controller/db/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    // IMPORTANT: Your old 'books' table doesn't have these columns.
    // We will add 'description' instead of 'author' and use the logged-in author's ID.
    $description = $_POST['author']; // Assuming 'author' field is for the book's author name, not your user
    $rating = $_POST['rating'];
    $cover = $_POST['cover'];
    $author_id = $_SESSION['user_id'] ?? 0; // Get logged-in user's ID

    // Use your existing 'books' table structure
    $stmt = $conn->prepare("INSERT INTO books (title, description, author_id, cover_image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $title, $description, $author_id, $cover);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
    $conn->close();
    exit(); // Stop the script after handling the POST request
}

// If GET request (Fetch Books)
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // This query should fetch from your actual 'books' table
    $result = $conn->query("SELECT * FROM books WHERE status = 'Published' ORDER BY book_id DESC");
    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    // Set the content type to JSON
    header('Content-Type: application/json');
    echo json_encode($books);
    $conn->close();
    exit(); // Stop the script after handling the GET request
}
?>