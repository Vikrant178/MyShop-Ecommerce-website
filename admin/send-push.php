<?php
require_once '../includes/db.php';
require_once '../vendor/autoload.php';
require_once 'includes/auth.php';

use Google\Auth\Credentials\ServiceAccountCredentials;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $message = $_POST['message'];

    // Load tokens from DB
    $tokens = [];
    $res = $conn->query("SELECT token FROM push_tokens");
    while ($row = $res->fetch_assoc()) {
        $tokens[] = $row['token'];
    }

    // Load Firebase credentials
    $serviceAccountPath = __DIR__ . '/firebase/firebase-service-account.json'; // replace with your actual JSON filename
    $projectId = 'ecommerce-store-52bb1'; // your Firebase project ID

    // Get access token using google/auth
    $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
    $credentials = new ServiceAccountCredentials($scopes, $serviceAccountPath);
    $accessToken = $credentials->fetchAuthToken()['access_token'];

    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json; UTF-8',
    ];

    $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

    $successCount = 0;

    foreach ($tokens as $token) {
        $notification = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body'  => $message,
                ],
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $successCount++;
        }
    }

    echo "<script>alert('Sent to $successCount devices.'); window.location.href='send-push.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Push Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; color: #212529; }
        .sidebar { background-color: #ffffff; height: 100vh; padding-top: 1rem; border-right: 1px solid #dee2e6; }
        .sidebar a { color: #343a40; padding: 0.75rem 1.25rem; display: block; text-decoration: none; }
        .sidebar a:hover { background-color: #e9ecef; color: #212529; }
        .topbar { background-color: #f1f3f5; padding: 1rem; border-bottom: 1px solid #dee2e6; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar col-md-2">
            <h5 class="text-center text-dark mb-4">Admin Panel</h5>
            <a href="index.php">Dashboard</a>
            <a href="add-product.php">Add Product</a>
            <a href="manage-products.php">Manage Products</a>
            <a href="add-category.php">Add Category</a>
            <a href="manage-categories.php">Manage Categories</a>
            <a href="manage-orders.php">Manage Orders</a>
            <a href="manage-users.php">Manage Users</a>
            <a href="send-push.php" class="fw-bold">Send Push Notification</a>
            <a href="bulk-upload.php">Bulk Upload Products</a>
            <a href="logout.php">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <div class="topbar">
                <h4>Send Push Notification</h4>
            </div>

            <div class="container mt-4">
                <form method="POST" class="bg-white p-4 rounded shadow-sm border">
                    <div class="mb-3">
                        <label class="form-label">Notification Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notification Message</label>
                        <textarea name="message" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Notification</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
