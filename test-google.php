<?php
require 'vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName('Test App');
$client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);

echo "Google Client initialized successfully!";
