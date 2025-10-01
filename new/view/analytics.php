<?php
session_start();

require_once '../controller/db/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header("Location: ./index.php");
    exit();
}
$author_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM books WHERE author_id = ?");
$stmt->execute([$author_id]);
$all_books = $stmt->fetchAll();

$total_published = 0;
$total_drafts = 0;
$total_views = 0;
$total_rating = 0;
$rated_books_count = 0;

foreach ($all_books as $book) {
    if ($book['status'] === 'Published') {
        $total_published++;
        // We only consider ratings and views for published books
        if (isset($book['rating']) && $book['rating'] > 0) {
            $total_rating += $book['rating'];
            $rated_books_count++;
        }
        $total_views += $book['views'] ?? 0;
    } else {
        $total_drafts++;
    }
}
$average_rating = ($rated_books_count > 0) ? round($total_rating / $rated_books_count, 2) : 'N/A';

$stmt_user = $pdo->prepare("SELECT username, profile_image FROM users WHERE user_id = ?");
$stmt_user->execute([$author_id]);
$user = $stmt_user->fetch();
$username = $user['username'] ?? 'Author';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Analytics</title>
    <!-- Your standard Bootstrap, Fonts, and Icons links -->
     <script src="httpshttps://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="../../asst/css/style.css">
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Loop through each of your books using PHP
    <?php foreach ($all_books as $book): ?>
        <?php
            // Make sure the dates are valid before trying to create a chart
            if (!empty($book['created_at']) && !empty($book['completed_at'])) {
                $startDate = new DateTime($book['created_at']);
                $endDate = new DateTime($book['completed_at']);
                $today = new DateTime();
                
                // Calculate the number of days for each stage
                $writingDays = $startDate->diff($endDate)->days;
                $publishedDays = $endDate->diff($today)->days;
        ?>
                // Create a new chart for this specific book
                new Chart(document.getElementById('timeline-<?php echo $book['book_id']; ?>'), {
                    type: 'bar',
                    data: {
                        labels: ['Writing Journey'],
                        datasets: [{
                            label: 'Writing Period (days)',
                            data: [<?php echo $writingDays; ?>],
                            backgroundColor: 'rgba(54, 162, 235, 0.6)', // Blue
                            borderWidth: 1
                        }, {
                            label: 'Published Period (days)',
                            data: [<?php echo $publishedDays; ?>],
                            backgroundColor: 'rgba(75, 192, 192, 0.6)', // Green
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y', // This makes the bar horizontal
                        scales: {
                            x: { stacked: true }, // Stacks the bars together
                            y: { stacked: true, display: false } // Hides the "Writing Journey" label
                        },
                        plugins: {
                            legend: { display: false }, // Hides the legend
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.raw} days`;
                                    }
                                }
                            }
                        }
                    }
                });
        <?php
            }
        ?>
    <?php endforeach; ?>
});
</script>
<body>
    <div class="d-flex" style="min-height: 100vh;">
        <?php include 'sidebar.php'; ?>

        <main class="flex-grow-1 p-4">
            <h1 class="main-title">My Analytics</h1>
            <p class="page-subtitle">Track the performance of your published works.</p>
            
            <!-- KPI Stat Cards -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card stat-card-books h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="fas fa-book-open fa-3x mb-3"></i>
                            <h3 class="card-title fw-bold"><?php echo $total_published; ?></h3>
                            <p class="card-text">Published Books</p>
                        </div>
                    </div>
                </div>
                 <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0" style="background-color: #6c757d; color: white;">
                        <div class="card-body text-center">
                            <i class="fas fa-pencil-ruler fa-3x mb-3"></i>
                            <h3 class="card-title fw-bold"><?php echo $total_drafts; ?></h3>
                            <p class="card-text">Drafts</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card stat-card-views h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="fas fa-star fa-3x mb-3"></i>
                            <h3 class="card-title fw-bold"><?php echo $average_rating; ?></h3>
                            <p class="card-text">Average Rating</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Book Performance Table -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <h2 class="h4 card-section-title">Book Performance Details</h2>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    
                                    <th>Views</th>
                                    <th>Rating</th>
                                    <th>Published On</th>
                                    <th>Timeline</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_books as $book): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                                        <td>
                                            <?php
                                                $status_class = $book['status'] === 'Published' ? 'bg-success' : 'bg-secondary';
                                            ?>
                                            <span class="badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($book['status']); ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($book['views'] ?? 0); ?></td>
                                        <td><?php echo htmlspecialchars($book['rating'] ?? 'N/A'); ?></td>
                                        <?php if ($book['status'] === 'Published'): ?>
                                                <!-- If the book is Published, show the date -->
                                                <td><?php echo date('M d, Y', strtotime($book['created_at'])); ?></td>
                                                <!-- And create the canvas for the graph -->
                                                <td><canvas id="timeline-<?php echo $book['book_id']; ?>" height="40"></canvas></td>
                                            <?php else: ?>
                                                <!-- If it's a Draft, show "N/A" in both columns -->
                                                <td>N/A</td>
                                                <td>N/A</td>
                                            <?php endif; ?>
                                    <td></td></tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>