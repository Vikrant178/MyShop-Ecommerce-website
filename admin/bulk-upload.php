<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';
require '../vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['product_file']) && $_FILES['product_file']['error'] === 0) {
        $fileName = $_FILES['product_file']['name'];
        $tmpPath = $_FILES['product_file']['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        try {
            $products = [];

            if ($fileExt === 'csv') {
                if (($handle = fopen($tmpPath, 'r')) !== false) {
                    fgetcsv($handle); // Skip header
                    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                        $products[] = $data;
                    }
                    fclose($handle);
                }
            } elseif ($fileExt === 'xlsx') {
                $spreadsheet = IOFactory::load($tmpPath);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                for ($i = 1; $i < count($rows); $i++) {
                    $products[] = $rows[$i];
                }
            } else {
                throw new Exception("Unsupported file format. Upload .csv or .xlsx");
            }

            $inserted = 0;
            foreach ($products as $row) {
                list($name, $price, $category_id, $image, $description) = $row;

                // Check for duplicate by name
                $check = $conn->prepare("SELECT id FROM products WHERE name = ?");
                $check->bind_param("s", $name);
                $check->execute();
                $check->store_result();

                if ($check->num_rows === 0) {
                    $stmt = $conn->prepare("INSERT INTO products (name, price, category_id, image, description) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sdiss", $name, $price, $category_id, $image, $description);
                    $stmt->execute();
                    $inserted++;
                }
            }

            $success = "Bulk upload successful! Products added: $inserted";
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "No file uploaded.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bulk Upload Products - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap Light -->
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
            color: #343a40;
            text-decoration: none;
            display: block;
            padding: 0.75rem 1.25rem;
        }
        .sidebar a:hover, .sidebar a.active {
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
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar col-md-2">
            <h5 class="text-center mb-4">Admin Panel</h5>
            <a href="index.php">Dashboard</a>
            <a href="add-product.php">Add Product</a>
            <a href="manage-products.php">Manage Products</a>
            <a href="add-category.php">Add Category</a>
            <a href="manage-categories.php">Manage Categories</a>
            <a href="manage-orders.php">Manage Orders</a>
            <a href="manage-users.php">Manage Users</a>
            <a href="send-push.php">Send Push Notification</a>
            <a href="bulk-upload.php" class="active">Bulk Upload Products</a>
            <a href="logout.php">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <div class="topbar d-flex justify-content-between align-items-center">
                <h4>Bulk Upload Products</h4>
                <span>Welcome, Admin</span>
            </div>

            <div class="container mt-4">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card p-4">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Upload File (.csv or .xlsx)</label>
                            <input type="file" name="product_file" class="form-control" accept=".csv,.xlsx" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>

                <div class="mt-5 p-4 bg-light border rounded">
                    <h5>📥 Download Sample Templates</h5>
                    <p>Use these templates to prepare your product upload files:</p>
                    <div class="d-flex gap-3">
                        <a href="download-template-excel.php" class="btn btn-success">Download Excel Template (.xlsx)</a>
                        <a href="download-template-csv.php" class="btn btn-info text-white">Download CSV Template (.csv)</a>
                    </div>
                    <p class="mt-3 text-muted small">
                        Required columns: <code>name | price | category_id | image | description</code><br>
                        Ensure uploaded images exist in <code>/assets/images/</code>.
                    </p>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
