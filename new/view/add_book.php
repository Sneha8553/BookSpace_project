<?php
session_start();
require_once '../controller/db/database.php';
$username = $_SESSION['username'];
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header("Location: ../login.php");
    exit();
}
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
                                    <h5>Add a New Book</h5>
                                    <p>Fill out the details below to publish your new work.</p>

                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <form action="../controller/add_book_process.php" method="POST"
                                                enctype="multipart/form-data">

                                                <input class="form-control form-control mb-3" type="text"
                                                    placeholder="Book Title" name="title" aria-label="Book Title">

                                                <textarea class="form-control form-control mb-3" type="text"
                                                    placeholder="Book Description / Synopsis" name="description"
                                                    aria-label="Book Description / Synopsis" rows="6"></textarea>

                                                <input class="form-control form-control mb-3" type="text"
                                                    placeholder="Genre (e.g., Fantasy, Sci-Fi)" name="genre"
                                                    aria-label="Genre (e.g., Fantasy, Sci-Fi)">

                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label">Upload a Cover
                                                        Image:</label>
                                                    <input class="form-control" type="file" id="formFile"
                                                        name="cover_image" accept="image/png, image/jpeg">
                                                </div>

                                                <!-- <div class="input-group">
                                                    <label for="cover">Upload a Cover Image:</label>
                                                    <input type="file" name="cover_image" id="cover"
                                                        accept="image/png, image/jpeg">
                                                </div> -->

                                                <!-- <button type="submit" class="btn btn-primary mb-3">Publish Book</button> -->
                                                <!-- <a href="author_dashboard.php" style="display: block; text-align: center; margin-top: 15px;">Cancel</a> -->
                                                <div class="input-group mb-3">
                                                <select class="form-select" name="status" aria-label="Status">
                                                    <option value="Draft" selected>Save as Draft</option>
                                                    <option value="Published">Publish Now</option>
                                                </select>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Submit Book</button> <a href="./my_books.php" type="button" class="btn btn-warning">Cancel</a>
                                            </form>
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
</body>

</html>