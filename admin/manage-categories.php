<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM categories WHERE id = $id");
    $success = "Category deleted successfully!";
}

$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories - Admin</title>
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
        <!-- Sidebar -->
        <div class="sidebar col-md-2">
            <h5 class="text-center text-dark mb-4">Admin Panel</h5>
            <a href="index.php">Dashboard</a>
            <a href="add-product.php">Add Product</a>
            <a href="manage-products.php">Manage Products</a>
            <a href="add-category.php">Add Category</a>
            <a href="manage-categories.php" class="fw-bold">Manage Categories</a>
            <a href="manage-orders.php">Manage Orders</a>
            <a href="manage-users.php">Manage Users</a>
            <a href="send-push.php">Send Push Notification</a>
            <a href="bulk-upload.php">Bulk Upload Products</a>
            <a href="logout.php">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <div class="topbar">
                <h4>Manage Categories</h4>
            </div>

            <div class="container mt-4">
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <table class="table table-light table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $categories->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this category?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
