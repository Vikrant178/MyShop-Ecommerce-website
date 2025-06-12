<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add or remove items
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_GET['action'] === 'add') {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    } elseif ($_GET['action'] === 'remove') {
        unset($_SESSION['cart'][$id]);
    }
    header('Location: cart.php');
    exit;
}

// Initialize cart items
$cartItems = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $qty) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $row['qty'] = $qty;
            $row['subtotal'] = $qty * $row['price'];
            $cartItems[] = $row;
            $total += $row['subtotal'];
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        .cart-container {
            background: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container my-5">
    <div class="cart-container">
        <h2 class="mb-4">🛒 Your Shopping Cart</h2>
        <?php if (!empty($cartItems)): ?>
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td>₹<?= number_format($item['price'], 2) ?></td>
                            <td>₹<?= number_format($item['subtotal'], 2) ?></td>
                            <td>
                                <a href="?action=remove&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <h4>Total: ₹<?= number_format($total, 2) ?></h4>
                <a href="checkout.php" class="btn btn-success btn-lg">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <p class="fs-4">🛍️ Your cart is currently empty.</p>
                <a href="index.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
