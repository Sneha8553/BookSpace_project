<!DOCTYPE html>
<html lang="en">
<?php
// rest-search.php
$q = urlencode($_POST['search'] ?? 'harry potter');           // your search query
$key = 'AIzaSyCOCuStWqupRkpuhuYgeG4tqGYUDIsizns';                   // from Cloud Console
$url = "https://www.googleapis.com/books/v1/volumes?q={$q}&key={$key}&maxResults=10";

$json = file_get_contents($url);
$data = json_decode($json, true);
// ECHO "json_decode($json, true)\n";
// if (!empty($data['items'])) {
//     foreach ($data['items'] as $item) {
//         $v = $item['volumeInfo'];
//         $title = $v['title'] ?? '—';
//         $authors = isset($v['authors']) ? implode(', ', $v['authors']) : '—';
//         echo $title . ' — ' . $authors . PHP_EOL;
//     }
// } else {
//     echo "No results\n";
// }
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Cinzel+Decorative:wght@700&display=swap"
        rel="stylesheet">
    <title>BookSpace</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Lora:wght@400;600&family=Poppins:wght@400;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5.0.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="../../asst/reader.css">
</head>

<body>
    <!-- <<<<<<< HEAD:reader.html -->

    <!-- Header -->
    <!-- ======= -->
    <div class="header">
        <div class="row">
            <div class="col-md-12 col-lg-1">
                <div class="logo">
                    <img src="logo.JPEG" alt="BookSpace Logo">
                </div>
            </div>
            <div class="col-md-12 col-lg-10">
                <form action="reader_dashboard.php" method="post">
                    <div class="search-bar">
                        <input type="text" name="search" placeholder="Search books...">
                    </div>
                </form>
            </div>
            <div class="col-md-12 col-lg-1">
                <div class="profile">👤   <?php session_start();       
                 echo $_SESSION['role'] ?> </div>
            </div>

        </div>
        <!-- <div class="logo">
            <img src="logo.JPEG" alt="BookSpace Logo">
        </div>
        <form action="reader_dashboard.php" method="post">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search books...">
            </div>
        </form>

        <div class="profile">👤 
            <?php //session_start();//        echo $_SESSION['role'] ?>
        </div> -->
    </div>

    <header>
        </div>
        <nav>
            <ul>
                <li><a href="reader_dashboard.php">Home</a></li>
                <li><a href="bookshelf.php">Bookshelf</a></li>
                <li><a href="cost.html">Cost</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="chatbot.html">AI Chatbot</a></li>
                <li><a href="authors.html">Authors</a></li>
            </ul>
        </nav>
        <div class="menu">
            <span class="more">☰</span>
        </div>
    </header>

    <div class="books">

        <?php
        if (!empty($data['items']))
            foreach ($data['items'] as $item): ?>
                <div class="book-card">
                    <img src="<?php echo $item['volumeInfo']['imageLinks']['thumbnail'] ?? 'https://via.placeholder.com/150'; ?>"
                        alt="Book Cover">
                    <div class="book-info">
                        <h3><?php echo $item['volumeInfo']['title'] ?? 'No Title'; ?></h3>
                        <p><?php echo isset($item['volumeInfo']['authors']) ? implode(', ', $item['volumeInfo']['authors']) : 'Unknown Author'; ?>
                        </p>
                        <div class="rating">⭐ <?php echo $item['volumeInfo']['averageRating'] ?? 'N/A'; ?></div>
                        <a href=" <?php echo $item['volumeInfo']['infoLink'] ?? 'N/A'; ?>" class="btn">✨ AI Summary</a>
                        <!-- infoLink -->
                    </div>
                </div>

            <?php endforeach; ?>
        <div class="book-card">
            <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1733931824i/221228045.jpg"
                alt="Book Cover">
            <div class="book-info">
                <h3>A Study in Drowning #2
                    A Theory of Dreaming</h3>
                <p>Ava Reid
                </p>
                <div class="rating">⭐ 3.84</div>
                <a href="#" class="btn">✨ AI Summary</a>
            </div>
        </div>

        <div class="book-card">
            <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1541428344i/17165596.jpg"
                alt="Book Cover">
            <div class="book-info">
                <h3>The Kite Runner</h3>
                <p>Khaled Hosseini</p>
                <div class="rating">⭐ 4.35</div>
                <a href="#" class="btn">✨ AI Summary</a>
            </div>
        </div>


    </div>
</body>

</html>