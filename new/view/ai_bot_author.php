<?php
session_start();
require_once '../controller/db/database.php';
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
    <style>
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 75vh; /* 75% of the viewport height */
        }
        .chat-messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1rem;
            border: 1px solid #dee2e6;
            background-color: #fff;
            border-radius: 0.5rem;
        }
        .message {
            margin-bottom: 1rem;
            display: flex;
            flex-direction: column;
        }
        .message.user {
            align-items: flex-end;
        }
        .message.bot {
            align-items: flex-start;
        }
        .message-bubble {
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            max-width: 80%;
        }
        .message.user .message-bubble {
            background-color: #0d6efd;
            color: white;
            border-bottom-right-radius: 0;
        }
        .message.bot .message-bubble {
            background-color: #e9ecef;
            color: #212529;
            border-bottom-left-radius: 0;
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

            <!-- <div class="welcome-text mb-3">
                <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
                <p class="text-muted">YOUR CREATIVE WORKSPACE AT A GLANCE.</p>
            </div> -->

            <!-- Cards -->
            <main class="row">
                <div class="col-12">
                    <h1 class="main-title">AI Assistant</h1>
            <p class="page-subtitle">Your creative co-pilot. Ask for ideas, help with writer's block, and more.</p>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="chat-container">
                        <!-- Where messages will be displayed -->
                        <div id="chat-messages" class="chat-messages mb-3">
                            <div class="message bot">
                                <div class="message-bubble">Hello! How can I help you with your writing today?</div>
                            </div>
                        </div>
                        
                        <!-- The input form -->
                        <form id="chat-form" class="d-flex gap-2">
                            <input type="text" id="message-input" class="form-control" placeholder="Ask for a plot idea..." autocomplete="off" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
                </div>
            </main>
        </div>
    </div>
    </div>
    </div>
</body>
<script>
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const chatMessages = document.getElementById('chat-messages');

        // Handle form submission
        chatForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Stop the page from reloading
            const userMessage = messageInput.value.trim();
            if (userMessage === '') return;

            // 1. Display the user's message immediately
            addMessage(userMessage, 'user');
            messageInput.value = ''; // Clear the input box

            // 2. Show a "thinking..." message
            addMessage('...', 'bot', true);

            // 3. Send the message to the back-end
            sendMessageToApi(userMessage);
        });

        // Function to add a message bubble to the chat window
        function addMessage(text, sender, isTyping = false) {
            if (isTyping) {
                // If a "thinking..." message already exists, don't add another
                if (document.getElementById('typing-indicator')) return;
            } else {
                // If we are adding a real message, remove any "thinking..." message
                const typingIndicator = document.getElementById('typing-indicator');
                if (typingIndicator) typingIndicator.parentElement.remove();
            }

            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', sender);

            const bubbleDiv = document.createElement('div');
            bubbleDiv.classList.add('message-bubble');
            bubbleDiv.textContent = text;
            if (isTyping) bubbleDiv.id = 'typing-indicator';

            messageDiv.appendChild(bubbleDiv);
            chatMessages.appendChild(messageDiv);

            // Auto-scroll to the bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Function to send data to your PHP backend
        async function sendMessageToApi(message) {
            try {
                // CORRECT PATH: Go up two levels to the controller
                const response = await fetch('../controller/ai_handler.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: message })
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                // 4. Display the AI's actual reply
                addMessage(data.reply, 'bot');

            } catch (error) {
                console.error('Fetch error:', error);
                addMessage('Sorry, something went wrong. Please try again.', 'bot');
            }
        }
    </script>
</html>