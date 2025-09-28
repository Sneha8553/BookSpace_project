<?php
session_start();
require_once '../controller/db/database.php';
// --- (Your PHP code remains the same) ---
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header("Location: ../index.php");
    exit();
}
$author_id = $_SESSION['user_id'];
// We need to fetch the user's data to display the username
$stmt_user = $pdo->prepare("SELECT username FROM users WHERE user_id = ?");
$stmt_user->execute([$author_id]);
$user = $stmt_user->fetch();
$username = $user['username'] ?? 'Author';

$stmt_books_count = $pdo->prepare("SELECT COUNT(*) FROM books WHERE author_id = ?");
$stmt_books_count->execute([$author_id]);
$total_books = $stmt_books_count->fetchColumn();
$stmt_views_count = $pdo->prepare("SELECT SUM(views) FROM books WHERE author_id = ?");
$stmt_views_count->execute([$author_id]);
$total_views = $stmt_views_count->fetchColumn() ?: 0;
// Note: You are fetching $my_books here, which is good practice.
$stmt_my_books = $pdo->prepare("SELECT book_id, title, cover_image, views, created_at FROM books WHERE author_id = ? ORDER BY created_at DESC");
$stmt_my_books->execute([$author_id]);
$my_books = $stmt_my_books->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookSpace Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Lora:wght@400;600&family=Poppins:wght@400;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5.0.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f6ecf9;
        }

        .sidebar {
            background-color: #243447;
            min-height: 100vh;
            padding: 20px;
            color: #fff;
        }

        .sidebar .nav-link {
            color: #fff;
            margin: 10px 0;
            border-radius: 6px;
            transition: 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #1c2e3f;
        }

        .logout-btn {
            width: 100%;
            background-color: #d9534f;
            border: none;
            padding: 10px;
            color: #fff;
            font-weight: 600;
            border-radius: 6px;
            margin-top: 20px;
        }

        .main-content {
            padding: 20px;
        }

        .welcome-text h2 {
            font-weight: 700;
            color: #2c1e4a;
        }

        .card-custom {
            border-radius: 10px;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .card-blue {
            background: #0066ff;
        }

        .card-teal {
            background: #008080;
        }

        .draft-box {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .draft-box input {
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .save-btn {
            width: 100%;
            background-color: #6c757d;
            color: #fff;
            border-radius: 6px;
            padding: 10px;
            border: none;
        }

        .add-book-btn {
            background: linear-gradient(90deg, #6a11cb, #2575fc);
            color: #fff;
            border-radius: 30px;
            padding: 10px 20px;
            border: none;
            font-weight: 600;
            /* position: absolute; */
            /* right: 20px;
      top: 10px; */
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 50%;
            border-width: 3px;
            border-style: solid;
            border-color: rgb(236, 240, 241);
            border-image: initial;
        }

        .sidebar h2 {
            font-family: "Cinzel Decorative", cursive;
            font-size: 1.5rem;
            text-align: center;
            margin: 0px 0px 20px;
        }

        .logoaa {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 50%;
            border-width: 3px;
            border-style: solid;
            border-color: rgb(236, 240, 241);
            border-image: initial;
        }

        .float-right {
            float: right;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <?php include 'sidebar.php'; ?>
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">


                <div class="container">

                    <div class="welcome-text mb-3">
                        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
                        <p class="text-muted">YOUR CREATIVE WORKSPACE AT A GLANCE.</p>
                    </div>

                    <!-- Cards -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card-custom card-blue">
                                <a href="my_books.php" style="text-decoration: none; color: white;">
                                    <i class="bi bi-book" style="font-size: 2rem;"></i>
                                    <h5>Manage My Books</h5>
                                    <h3><?php echo $total_books; ?></h3>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card-custom card-teal">
                                <i class="bi bi-eye" style="font-size: 2rem;"></i>
                                <h5>Total Views</h5>
                                <h3><?php echo $total_views; ?></h3>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">

                            <!-- Add Book Button -->

                            <!-- Published Works -->
                            <div class="published-works mt-5">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h4>My Published Works</h4>
                                        <?php if (empty($my_books)): ?>
                                            <p>You haven't published any books yet. Click "Add New Book" to get started!</p>
                                        <?php else: ?>
                                            <!-- A simple preview of recent books can go here -->
                                            <p>You have <?php echo count($my_books); ?> books. </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- <button class="add-book-btn float-right"><i class="bi bi-plus-circle"></i> Add New Book</button> -->
                                        <a href="add_book.php" class="btn add-book-btn float-right mb-3"><i
                                                class="bi bi-plus-circle"></i> Add New Book</a>
                                    </div>
                                </div>
                                <div class="draft-box">
                                    <h5>Have a new idea?</h5>
                                    <p class="text-muted">Quickly save a draft for later.</p>
                                    <form action="../controller/quick_draft.php" method="POST">
                                        <input type="text" name="draft_title" class="form-control"
                                            placeholder="Enter a book title...">
                                        <button type="submit" class="btn btn-secondary ">Save Draft</button>
                                    </form>
                                    <!-- <button type="button" class="btn btn-secondary">Primary</button> -->
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status === 'draftsaved') {
            Swal.fire({
                title: 'Draft Saved!',
                text: 'Your new book idea has been saved. You can manage it from the "Manage My Books" page.',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500, // A little longer for readability
                timerProgressBar: true
            });
        } else if (status === 'empty') {

            Swal.fire({
                title: 'Error!',
                text: 'Title cannot be empty. Please enter a valid title.',
                icon: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500, // A little longer for readability
                timerProgressBar: true
            });
        }
    });
</script>
        <script src="../asst/js/style.js"></script>

</html>