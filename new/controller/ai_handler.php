<?php
session_start();
// Security: Only allow access if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

// --- CONFIGURATION ---
// curl "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent" \
//   -H 'Content-Type: application/json' \
//   -H 'X-goog-api-key: AIzaSyCRooi63goFV3iTjxXnqEMK-feJnjaCDe0' \
//   -X POST \
//   -d '{
//     "contents": [
//       {
//         "parts": [
//           {
//             "text": "Explain how AI works in a few words"
//           }
//         ]
//       }
//     ]
//   }'
$apiKey = 'AIzaSyCRooi63goFV3iTjxXnqEMK-feJnjaCDe0'; // <-- PASTE YOUR GEMINI API KEY HERE
$apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey;

// --- RECEIVE AND VALIDATE USER INPUT ---
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

if (empty($userMessage)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Message is empty']);
    exit();
}

// --- PREPARE DATA FOR THE API ---
$data = [
    'contents' => [
        [
            'parts' => [
                ['text' => $userMessage]
            ]
        ]
    ]
];
$jsonData = json_encode($data);

// --- SEND THE REQUEST TO THE GEMINI API using cURL ---
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
$response = curl_exec($ch);
curl_close($ch);

// --- PROCESS THE RESPONSE AND SEND IT BACK TO THE FRONT-END ---
$responseData = json_decode($response, true);

// Extract the AI's text response. The path may vary slightly, but this is standard.
$aiMessage = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, I could not process that request.';

// Set the header to indicate the response is JSON
header('Content-Type: application/json');
echo json_encode(['reply' => $aiMessage]);
?>