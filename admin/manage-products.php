<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Delete product if requested
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $id");
    $success = "Product deleted successfully!";
}

// Fetch all products
$products = $conn->query("SELECT p.*, c.name AS category_name 
                          FROM products p 
                          JOIN categories c ON p.category_id = c.id 
                          ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; color: #212529; }
        .sidebar { background-color: #ffffff; height: 100vh; padding-top: 1rem; border-right: 1px solid #dee2e6; }
        .sidebar a { color: #343a40; padding: 0.75rem 1.25rem; display: block; text-decoration: none; }
        .sidebar a:hover { background-color: #e9ecef; color: #212529; }
        .topbar { background-color: #f1f3f5; padding: 1rem; border-bottom: 1px solid #dee2e6; }
        .table-light th, .table-light td { background-color: #ffffff; }
        .table-light tr:hover td { background-color: #f8f9fa; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.8rem; }
        img.thumb { height: 50px; object-fit: cover; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar col-md-2">
            <h5 class="text-center text-dark mb-4">Admin Panel</h5>
            <a href="index.php">Dashboard</a>
            <a href="add-product.php">Add Product</a>
            <a href="manage-products.php" class="fw-bold">Manage Products</a>
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
            <div class="topbar d-flex justify-content-between align-items-center">
                <h4>Manage Products</h4>
            </div>

            <div class="container mt-4">
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <table class="table table-light table-bordered">
                    <thead class="table-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $products->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><img src="../images/<?php echo $row['image']; ?>" class="thumb"></td>
                            <td><?php echo $row['name']; ?></td>
                            <td>₹<?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo $row['category_name']; ?></td>
                            <td><?php echo isset($row['description']) ? substr($row['description'], 0, 50) . '...' : 'N/A'; ?></td>
                            <td>
                                <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this product?')">Delete</a>
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
