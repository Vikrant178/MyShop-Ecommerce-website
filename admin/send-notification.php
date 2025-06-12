<?php
require __DIR__ . '/../vendor/autoload.php'; // Load Composer dependencies

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

// Path to JSON file inside admin folder
$serviceAccountPath = __DIR__ . '/firebase/firebase-service-account.json';
$firebaseProjectId = 'ecommerce-store-52bb1'; // replace with your actual Firebase project ID

// Step 1: Generate access token dynamically
$scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
$credentials = new ServiceAccountCredentials($scopes, $serviceAccountPath);
$httpHandler = HttpHandlerFactory::build();
$accessToken = $credentials->fetchAuthToken($httpHandler)['access_token'];

// Step 2: Replace with a real token from your DB or testing device
$deviceToken = 'dFMRCNlwzfEs_V0gU_1K9_:APA91bF0KwY3Q1FlXOiLxVfnRQZNr4PDRzi-UL6lcqDJc4nCh_jYW-B2A8a0T5BRjhLeU39BO6qf75Jp0COWe53l78c40B4sn0iR07Uf6jrNMH0s2_f1yEs';

// Step 3: Prepare notification
$notification = [
    'message' => [
        'token' => $deviceToken,
        'notification' => [
            'title' => 'New Notification',
            'body'  => 'This message is sent from PHP using Firebase V1 API!',
        ],
        'data' => [
            'customKey1' => 'value1',
            'customKey2' => 'value2',
        ]
    ]
];

// Step 4: Send to Firebase
$headers = [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json; UTF-8',
];

$url = "https://fcm.googleapis.com/v1/projects/{$firebaseProjectId}/messages:send";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

// Step 5: Show result
if ($error) {
    echo "Curl Error: " . $error;
} else {
    echo "Response:\n";
    echo $response;
}
