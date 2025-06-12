<?php 
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category_id = $_POST['category'];
    $description = $_POST['description'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    $imageDir = "../assets/images/";
    if (!is_dir($imageDir)) {
        mkdir($imageDir, 0777, true);
    }

    if (move_uploaded_file($tmp, $imageDir . $image)) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, category_id, image, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdiss", $name, $price, $category_id, $image, $description);
        $stmt->execute();

        $success = "Product added successfully!";
    } else {
        $error = "Failed to upload image.";
    }
}

$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        .sidebar {
            background-color: #e9ecef;
            height: 100vh;
            padding-top: 1rem;
        }
        .sidebar a {
            color: #212529;
            padding: 0.75rem 1.25rem;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #dee2e6;
            color: #000;
        }
        .topbar {
            background-color: #ffffff;
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        .card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .form-control, .form-select {
            background-color: #ffffff;
            color: #212529;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar col-md-2">
            <h5 class="text-center text-dark mb-4">Admin Panel</h5>
            <a href="index.php">Dashboard</a>
            <a href="add-product.php" class="fw-bold">Add Product</a>
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
            <div class="topbar d-flex justify-content-between align-items-center">
                <h4 class="m-0">Add Product</h4>
            </div>

            <div class="container mt-4">
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php elseif (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card p-4">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" name="price" step="0.01" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php while($cat = $categories->fetch_assoc()): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
