<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $success = "Category added successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Category - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; color: #212529; }
        .sidebar { background-color: #ffffff; height: 100vh; padding-top: 1rem; border-right: 1px solid #dee2e6; }
        .sidebar a { color: #343a40; padding: 0.75rem 1.25rem; display: block; text-decoration: none; }
        .sidebar a:hover { background-color: #e9ecef; color: #212529; }
        .topbar { background-color: #f1f3f5; padding: 1rem; border-bottom: 1px solid #dee2e6; }
        .form-control { background-color: #ffffff; color: #212529; border: 1px solid #ced4da; }
        .card { background-color: #ffffff; border: 1px solid #dee2e6; }
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
            <a href="add-category.php" class="fw-bold">Add Category</a>
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
                <h4>Add Category</h4>
            </div>

            <div class="container mt-4">
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="card p-4">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
