<?php
require_once __DIR__ . '/vendor/autoload.php';

// Path to your service account key JSON file
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/firebase-service-account.json');

$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope('https://www.googleapis.com/auth/firebase.messaging');

$token = $client->fetchAccessTokenWithAssertion();

if (isset($token['access_token'])) {
    echo "Access Token:\n" . $token['access_token'];
} else {
    echo "Failed to fetch token:\n";
    print_r($token);
}
