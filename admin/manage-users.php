<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Fetch users
$users = $conn->query("SELECT id, name, email, contact, address FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin</title>
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
        <a href="manage-orders.php">Manage Orders</a>
        <a href="manage-users.php" class="fw-bold">Manage Users</a>
        <a href="send-push.php">Send Push Notification</a>
        <a href="bulk-upload.php">Bulk Upload Products</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="col-md-10">
        <div class="topbar">
            <h4>Manage Users</h4>
        </div>

        <div class="container mt-4">
            <table class="table table-light table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sn = 1; while ($row = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= $sn++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['contact']) ?></td>
                        <td style="max-width: 200px; white-space: pre-wrap;"><?= nl2br(htmlspecialchars($row['address'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
