<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = intval($_POST['order_id']);
    $status = trim($_POST['status']);

    $allowed = ['Pending', 'Processed', 'For Shipping', 'Out for Delivery', 'Delivered'];
    if (!in_array($status, $allowed)) {
        die('Invalid status value.');
    }

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $orderId);

    if ($stmt->execute()) {
        header("Location: manage-orders.php?success=1");
        exit;
    } else {
        echo "Failed to update status.";
    }
}
?>
