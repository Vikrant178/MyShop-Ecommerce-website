<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;

// Load Firebase service account credentials
$credentialsPath = __DIR__ . '/firebase-service-account.json';  // Path to your JSON key
$credentials = new ServiceAccountCredentials(
    'https://www.googleapis.com/auth/firebase.messaging',
    $credentialsPath
);

// Get an access token
$authToken = $credentials->fetchAuthToken();
$accessToken = $authToken['access_token'];

// Your Firebase project ID
$projectId = 'ecommerce-store-52bb1'; 

// FCM endpoint (V1)
$url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

// Message structure
$payload = [
    "message" => [
        "token" => "dFMRCNlwzfEs_V0gU_1K9_:APA91bF0KwY3Q1FlXOiLxVfnRQZNr4PDRzi-UL6lcqDJc4nCh_jYW-B2A8a0T5BRjhLeU39BO6qf75Jp0COWe53l78c40B4sn0iR07Uf6jrNMH0s2_f1yEs", // Replace with the target device token
        "notification" => [
            "title" => "Test Notification",
            "body"  => "Hello from Firebase V1 API using PHP!"
        ]
    ]
];

// Send the push notification
$client = new Client();
$response = $client->post($url, [
    'headers' => [
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type'  => 'application/json',
    ],
    'json' => $payload
]);

echo "Response: " . $response->getBody();
