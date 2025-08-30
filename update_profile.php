<?php
session_start();
require_once 'database.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("SELECT username, email, first_name, last_name, profile_image FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $currentUser = $stmt->fetch();
    } catch (PDOException $e) {
        // Use die() for debugging to see the error instantly
        die("Database error fetching user: " . $e->getMessage());
    }
    
    $newUsername = $_POST['username'] ?? '';
    $newEmail = $_POST['email'] ?? '';
    $newFirstName = $_POST['first_name'] ?? '';
    $newLastName = $_POST['last_name'] ?? '';
    $newProfileImagePath = $currentUser['profile_image']; 
    $imageWasUploaded = false; // A flag to track if a new image was submitted

    // --- Handle the File Upload ---
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = uniqid() . '-' . basename($_FILES["profile_image"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath)) {
            $newProfileImagePath = $targetFilePath;
            $imageWasUploaded = true; // Set the flag to true
        }
    }

    // --- Compare old data with new data ---
    // A change is detected if text is different OR if a new image was successfully uploaded.
    if ($currentUser['username'] !== $newUsername || 
        $currentUser['email'] !== $newEmail || 
        $currentUser['first_name'] !== $newFirstName || 
        $currentUser['last_name'] !== $newLastName ||
        
        $imageWasUploaded === true) { // Use the flag for comparison
        
        // --- Data is different, proceed with the update ---
        try {
            $sql = "UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ?, profile_image = ? WHERE user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$newUsername, $newEmail, $newFirstName, $newLastName, $newProfileImagePath, $user_id]);

            $_SESSION['username'] = $newUsername;
            header("Location: my_profile.php?status=success");
            exit();

        } catch (PDOException $e) {
            die("Database error updating user: " . $e->getMessage());
        }

    } else {
        // No changes were made
        header("Location: my_profile.php?status=nochange");
        exit();
    }

} else {
    header("Location: index.html");
    exit();
}
?>