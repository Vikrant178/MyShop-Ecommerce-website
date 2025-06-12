<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_SESSION['cart'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'COD';

    // Basic validation
    if (!$name || !$email || !$phone || !$address || !preg_match('/^[0-9]{10}$/', $phone)) {
        die("Invalid input. Please go back and check the form.");
    }

    // Insert order with payment method
    $stmt = $conn->prepare("INSERT INTO orders (user_name, user_email, user_phone, user_address, payment_method) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $address, $payment_method);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert each cart item
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt_item->bind_param("iii", $order_id, $product_id, $quantity);
        $stmt_item->execute();
    }

    // Clear cart and redirect
    $_SESSION['cart'] = [];
    header("Location: checkout-success.php");
    exit();
} else {
    echo "No items in cart or invalid access.";
}
?>
