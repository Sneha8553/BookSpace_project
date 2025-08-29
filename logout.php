<?php
session_start();

$_SESSION = array();

session_destroy();
$redirect_delay = 2;
$redirect_url = 'index.html';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logging Out | bookSpace</title>
        <meta http-equiv="refresh" content="<?php echo $redirect_delay; ?>;url=<?php echo $redirect_url; ?>">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Cinzel+Decorative:wght@700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(145deg, #f3e7fe, #fdeef9);
            margin: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }

        .logout-container {
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white */
            padding: 40px 60px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .logout-container h1 {
            font-family: 'Cinzel Decorative', cursive;
            font-size: 2.5em;
            margin-top: 0;
            margin-bottom: 20px;
            color: #2c3e50; /* Dark blue-gray from your sidebar */
        }

        .logout-container p {
            font-size: 1.2em;
            color: #555;
        }

        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border-left-color: #2980b9; /* The primary blue from your buttons */
            margin: 20px auto 0 auto;
            animation: spin 1s ease infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>

    <div class="logout-container">
        <h1>bookSpace</h1>
        <p>You have been successfully logged out.</p>
        <p>Redirecting you to the login page...</p>
        <div class="spinner"></div>
    </div>

</body>
</html>