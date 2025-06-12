<?php 
session_start();
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    header("Location: login.php");
    exit;
}

require_once '../includes/db.php';

$user_email = $_SESSION['user']['email'];

$orders = $conn->query("
    SELECT o.id, o.created_at, o.status, o.user_address, o.user_phone, o.payment_method,
           GROUP_CONCAT(p.name SEPARATOR ', ') AS products
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_email = '$user_email'
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
    body { background-color: #f8f9fa; }
    .card { margin-bottom: 30px; }

    .tracker-container {
        position: relative;
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .progress {
        height: 6px;
        background-color: #dee2e6;
        position: absolute;
        top: 24px;
        left: 0;
        width: 100%;
        z-index: 1;
        border-radius: 50px;
    }

    .progress-bar {
        height: 6px;
        background-color: #198754;
        transition: width 0.6s ease;
        border-radius: 50px;
        z-index: 2;
        position: relative;
    }

    .step-icons {
        display: flex;
        justify-content: space-between;
        position: relative;
        z-index: 3;
    }

    .step {
        text-align: center;
        flex: 1;
        position: relative;
        z-index: 4;
    }

    .circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #ccc;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        transition: background 0.3s;
    }

    .step.active .circle {
        background: #198754;
    }

    .label {
        margin-top: 10px;
        font-size: 0.9rem;
    }

    .step.active .label {
        font-weight: bold;
        color: #198754;
    }

    .status-time {
        font-size: 0.75rem;
        color: #888;
    }
</style>
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">My Orders</h3>

    <?php 
    $statusList = ['Pending', 'Processed', 'For Shipping', 'Out for Delivery', 'Delivered'];
    $statusIcons = [
        'Pending' => 'fa-hourglass-start',
        'Processed' => 'fa-gear',
        'For Shipping' => 'fa-box',
        'Out for Delivery' => 'fa-truck-fast',
        'Delivered' => 'fa-check-circle'
    ];

    while ($row = $orders->fetch_assoc()):
        $currentIndex = array_search($row['status'], $statusList);
        $progressPercent = $currentIndex !== false ? (($currentIndex) / (count($statusList) - 1)) * 100 : 0;
    ?>
        <div class="card shadow-sm p-4 rounded-4">
            <h5>Order #<?= $row['id'] ?></h5>
            <p><strong>Products:</strong> <?= htmlspecialchars($row['products']) ?></p>
            <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($row['user_address'])) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($row['user_phone']) ?></p>
            <p><strong>Payment Method:</strong> <?= $row['payment_method'] ?></p>
            <p><strong>Order Date:</strong> <?= $row['created_at'] ?></p>
            
            <div class="tracker-container">
                <div class="progress">
                    <div class="progress-bar" style="width: <?= $progressPercent ?>%;"></div>
                </div>
                <div class="step-icons">
                    <?php foreach ($statusList as $i => $status): ?>
                        <div class="step <?= $i <= $currentIndex ? 'active' : '' ?>">
                            <div class="circle"><i class="fa <?= $statusIcons[$status] ?>"></i></div>
                            <div class="label"><?= $status ?></div>
                            <div class="status-time"><?= $i <= $currentIndex ? '✓' : '' ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
