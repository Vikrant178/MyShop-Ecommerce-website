<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit;
}

$product_id = $_POST['product_id'];
$rating = $_POST['rating'];
$review_text = $_POST['review_text'];
$user_email = $_SESSION['user']['email'];

if ($rating < 1 || $rating > 5 || empty($product_id)) {
    die("Invalid input.");
}

$stmt = $conn->prepare("INSERT INTO product_reviews (product_id, user_email, rating, review_text) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isis", $product_id, $user_email, $rating, $review_text);
$stmt->execute();

header("Location: product.php?id=" . $product_id);
