<?php
$host = "localhost";   // XAMPP default
$user = "root";        // XAMPP default user
$pass = "";            // XAMPP default password
$db   = "bookspace";

// Connect to DB
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If POST request (Add Book)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $rating = $_POST['rating'];
    $cover = $_POST['cover'];

    $stmt = $conn->prepare("INSERT INTO books (title, author, rating, cover) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $title, $author, $rating, $cover);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
}

// If GET request (Fetch Books)
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $result = $conn->query("SELECT * FROM books ORDER BY id DESC");
    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    echo json_encode($books);
}

$conn->close();
?>
