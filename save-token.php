<?php
// db.php should contain your DB connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';

    if (!empty($token)) {
        // Example: Save token to `user_tokens` table
        $stmt = $conn->prepare("INSERT INTO user_tokens (token) VALUES (?) ON DUPLICATE KEY UPDATE token = VALUES(token)");
        $stmt->bind_param("s", $token);

        if ($stmt->execute()) {
            echo "Token saved successfully.";
        } else {
            echo "Failed to save token.";
        }

        $stmt->close();
    } else {
        echo "Token is empty.";
    }
}
?>
