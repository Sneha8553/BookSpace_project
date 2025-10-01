<?php
session_start();
require_once '../controller/db/database.php';
// --- (Your PHP code remains the same) ---
// --- (Your PHP code at the top remains the same) ---
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') { /* ... */
}
$author_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$stmt_my_books = $pdo->prepare("SELECT book_id, title, genre, views, created_at, status FROM books WHERE author_id = ? ORDER BY created_at DESC");
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
                    <main class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <!-- <div class="works-header"> -->
                                    <h5>Manage My Works</h5>
                                    <div class="d-flex gap-2">

                                        <!-- NEW LINK to switch to the card view -->
                                        <!-- This link should be inside new/view/my_books.php -->

                                        <a href="reader/bookshelf.php?author_id=<?php $_SESSION['id'] = $_SESSION['user_id']; ?>"
                                            class="btn add-book-btn float-right mb-3">
                                            View as Reader
                                        </a>
                                        <a href="add_book.php" class="btn add-book-btn float-right mb-3"><i
                                                class="bi bi-plus-circle"></i> Add New Book</a>

                                        <!-- </div> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <p class="page-subtitle">Here you can edit or delete your published
                                                    books.
                                                </p>

                                                <div class="my-works">
                                                    <?php if (empty($my_books)): ?>
                                                        <!-- This message shows ONLY if there are no books -->
                                                        <p>You haven't published any books yet. <a href="add_book.php">Click
                                                                here to add
                                                                one!</a>
                                                        </p>
                                                    <?php else: ?>
                                                        <!-- The table and its header are now correctly placed -->
                                                        <!--  -->
                                                        <div class="row col-md-12 mb-3">
                                                            <div class="table-responsive">
                                                                <table class="table table-success table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Title</th>
                                                                            <th>Status</th>
                                                                            <th>Genre</th>
                                                                            <th>Views</th>
                                                                            <th>Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <!-- The loop to display each book row -->
                                                                        <?php foreach ($my_books as $book): ?>
                                                                            <tr>
                                                                                <td><strong><?php echo htmlspecialchars($book['title']); ?></strong>
                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                    $status_class = '';
                                                                                    if ($book['status'] === 'Published') {
                                                                                        $status_class = 'bg-success';
                                                                                    } elseif ($book['status'] === 'Draft') {
                                                                                        $status_class = 'bg-secondary';
                                                                                    } else {
                                                                                        $status_class = 'bg-warning text-dark';
                                                                                    }
                                                                                    ?>
                                                                                    <span
                                                                                        class="badge <?php echo $status_class; ?>">
                                                                                        <?php echo htmlspecialchars($book['status']); ?>
                                                                                    </span>
                                                                                </td>
                                                                                <td><?php echo htmlspecialchars($book['genre'] ?? 'N/A'); ?>
                                                                                </td>
                                                                                <td><?php echo htmlspecialchars($book['views'] ?? 0); ?>
                                                                                </td>
                                                                                <td class="action-links">
                                                                                    <a href="./edit_book.php?id=<?php echo $book['book_id']; ?>"
                                                                                        type="button" class="btn btn-success"><i
                                                                                            class="fas fa-pencil-alt"></i>
                                                                                        Edit</a>
                                                                                    <a href="../controller/delete_book.php?id=<?php echo $book['book_id']; ?>"
                                                                                        type="button" class="btn btn-danger"
                                                                                        onclick="return confirm('Are you sure?');"><i
                                                                                            class="fas fa-trash-alt"></i>
                                                                                        Delete</a>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                    </main>

                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../asst/js/style.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            if (status === 'published') {
                Swal.fire({
                    title: 'Published!',
                    text: 'Your new book idea has been saved. You can manage it from the "Manage My Books" page.',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3500, // A little longer for readability
                    timerProgressBar: true
                });

            } else if (status === 'Draft') {
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
            } else {

                Swal.fire({
                    title: 'Error!',
                    text: 'Nothing Updated!',
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
</body>

</html>