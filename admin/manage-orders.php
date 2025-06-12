<?php 
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Fetch orders and associated products with address
$orders = $conn->query("
    SELECT o.id, o.user_name, o.user_email, o.user_phone, o.user_address, o.status, o.created_at, o.payment_method,
           GROUP_CONCAT(p.name SEPARATOR ', ') AS products
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; color: #212529; }
        .sidebar { background-color: #ffffff; height: 100vh; padding-top: 1rem; border-right: 1px solid #dee2e6; }
        .sidebar a { color: #343a40; padding: 0.75rem 1.25rem; display: block; text-decoration: none; }
        .sidebar a:hover { background-color: #e9ecef; color: #212529; }
        .topbar { background-color: #f1f3f5; padding: 1rem; border-bottom: 1px solid #dee2e6; }
        .table-light th, .table-light td { background-color: #ffffff; border-color: #dee2e6; }
        .table-light tr:hover td { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="sidebar col-md-2">
            <h5 class="text-center text-dark mb-4">Admin Panel</h5>
            <a href="index.php">Dashboard</a>
            <a href="add-product.php">Add Product</a>
            <a href="manage-products.php">Manage Products</a>
            <a href="add-category.php">Add Category</a>
            <a href="manage-categories.php">Manage Categories</a>
            <a href="manage-orders.php" class="fw-bold">Manage Orders</a>
            <a href="manage-users.php">Manage Users</a>
            <a href="send-push.php">Send Push Notification</a>
            <a href="bulk-upload.php">Bulk Upload Products</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="col-md-10">
            <div class="topbar">
                <h4>Manage Orders</h4>
            </div>

            <div class="container mt-4">
                <table class="table table-light table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Products</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Date</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                            <td><?= htmlspecialchars($row['user_email']) ?></td>
                            <td><?= htmlspecialchars($row['products']) ?></td>
                            <td style="max-width: 200px; white-space: pre-wrap;"><?= nl2br(htmlspecialchars($row['user_address'])) ?></td>
                            <td><?= htmlspecialchars($row['user_phone']) ?></td>
                            <td><?php echo $row['payment_method']; ?></td>
                            <td>
    <form method="POST" action="update-order-status.php" class="d-flex">
        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
        <select name="status" class="form-select form-select-sm me-2">
            <?php 
                $statuses = ['Pending', 'Processed', 'For Shipping', 'Out for Delivery', 'Delivered'];
                foreach ($statuses as $status): 
            ?>
                <option value="<?= $status ?>" <?= $row['status'] === $status ? 'selected' : '' ?>>
                    <?= $status ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-sm btn-primary">Update</button>
    </form>
</td>
                            <td><?= $row['created_at'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
