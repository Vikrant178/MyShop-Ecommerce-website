<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Statistics
$productCount = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$orderCount = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$categoryCount = $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()['total'];
$userCount = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

// Recent Orders
$recentOrders = $conn->query("SELECT user_name, status, created_at FROM orders ORDER BY created_at DESC LIMIT 5");

// Order counts for the last 7 days
$orderData = $conn->query("
    SELECT DATE(created_at) AS order_date, COUNT(*) AS total
    FROM orders
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(created_at)
    ORDER BY order_date ASC
");

$orderDates = [];
$orderCounts = [];

while ($row = $orderData->fetch_assoc()) {
    $orderDates[] = date("M j", strtotime($row['order_date']));
    $orderCounts[] = (int)$row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background-color: #ffffff; height: 100vh; padding-top: 1rem; border-right: 1px solid #dee2e6; }
        .sidebar a { color: #343a40; padding: 0.75rem 1.25rem; display: block; text-decoration: none; }
        .sidebar a:hover { background-color: #e9ecef; color: #212529; }
        .topbar { background-color: #f1f3f5; padding: 1rem; border-bottom: 1px solid #dee2e6; }
        .card { transition: transform 0.2s ease; }
        .card:hover { transform: translateY(-3px); }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar col-md-2">
        <h5 class="text-center text-dark mb-4">Admin Panel</h5>
        <a href="index.php" class="fw-bold">Dashboard</a>
        <a href="add-product.php">Add Product</a>
        <a href="manage-products.php">Manage Products</a>
        <a href="add-category.php">Add Category</a>
        <a href="manage-categories.php">Manage Categories</a>
        <a href="manage-orders.php">Manage Orders</a>
        <a href="manage-users.php">Manage Users</a>
        <a href="send-push.php">Send Push Notification</a>
        <a href="bulk-upload.php">Bulk Upload Products</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="col-md-10">
        <div class="topbar">
            <h4>Welcome to Admin Dashboard</h4>
        </div>

        <div class="container mt-4">
            <!-- Stats Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-box-seam fs-2 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Total Products</h6>
                                <h4 class="fw-bold"><?= $productCount ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-cart-check fs-2 text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Total Orders</h6>
                                <h4 class="fw-bold"><?= $orderCount ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-tags fs-2 text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Total Categories</h6>
                                <h4 class="fw-bold"><?= $categoryCount ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-people fs-2 text-danger"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Total Users</h6>
                                <h4 class="fw-bold"><?= $userCount ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders and Chart -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white fw-bold">
                            <i class="bi bi-clock-history me-2"></i>Recent Orders
                        </div>
                        <ul class="list-group list-group-flush">
                            <?php while ($order = $recentOrders->fetch_assoc()): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($order['user_name']) ?></strong><br>
                                        <small class="text-muted"><?= date("d M Y", strtotime($order['created_at'])) ?></small>
                                    </div>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($order['status']) ?></span>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white fw-bold">
                            <i class="bi bi-bar-chart-line me-2"></i>Order Trend
                        </div>
                        <div class="card-body">
                            <canvas id="ordersChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('ordersChart').getContext('2d');
const ordersChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($orderDates) ?>,
        datasets: [{
            label: 'Orders per Day',
            data: <?= json_encode($orderCounts) ?>,
            fill: true,
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            borderColor: '#0d6efd',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
</body>
</html>
