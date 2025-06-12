<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$product_id = $_GET['id'] ?? 0;

// Get product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

// Insert new review
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_email = trim($_POST['email']);
    $user_name = trim($_POST['name']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($user_email && $rating && $comment) {
        $insert = $conn->prepare("INSERT INTO product_reviews (product_id, user_email, rating, review_text, created_at) VALUES (?, ?, ?, ?, NOW())");
        $insert->bind_param("isis", $product_id, $user_email, $rating, $comment);
        $insert->execute();
        header("Location: product.php?id=$product_id");
        exit;
    }
}

// Get reviews
$stmt = $conn->prepare("SELECT * FROM product_reviews WHERE product_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Average rating
$stmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM product_reviews WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$avg_result = $stmt->get_result()->fetch_assoc();
$average_rating = round($avg_result['avg_rating'] ?? 0, 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($product['name']); ?> - Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .product-img {
            max-width: 100%;
            border-radius: 10px;
            height: 300px;
            object-fit: cover;
        }
        .star {
            color: #ffc107;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container my-4">
    <div class="row">
        <div class="col-md-5">
            <img src="images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-img">
        </div>
        <div class="col-md-7">
            <h2><?= htmlspecialchars($product['name']); ?></h2>
            <p class="mb-1">₹<?= number_format($product['price']); ?></p>
            <div class="mb-2">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="bi <?= $i <= $average_rating ? 'bi-star-fill' : ($i - 0.5 <= $average_rating ? 'bi-star-half' : 'bi-star') ?> star"></i>
                <?php endfor; ?>
                <span class="ms-2">(<?= $average_rating ?>/5)</span>
            </div>
            <a href="cart.php?action=add&id=<?= $product['id']; ?>" class="btn btn-primary">Add to Cart</a>
        </div>
    </div>

    <hr class="my-4">

    <!-- Review Form -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h5>Leave a Review</h5>
            <form method="POST">
                <div class="mb-2">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label for="email" class="form-label">Your Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label for="rating" class="form-label">Rating</label>
                    <select name="rating" id="rating" class="form-select" required>
                        <option value="">Select rating</option>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?= $i; ?>"><?= $i; ?> Star<?= $i > 1 ? 's' : ''; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Submit Review</button>
            </form>
        </div>
    </div>

    <!-- Reviews -->
    <div class="row">
        <div class="col-md-8">
            <h5>Reviews</h5>
            <?php if (count($reviews) > 0): ?>
                <?php foreach ($reviews as $r): ?>
                    <div class="border p-3 mb-3 rounded bg-white">
                        <strong><?= htmlspecialchars($r['user_email']); ?></strong>
                        <div>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi <?= $i <= $r['rating'] ? 'bi-star-fill' : 'bi-star' ?> star"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="mb-1"><?= nl2br(htmlspecialchars($r['review_text'])); ?></p>
                        <small class="text-muted"><?= date("d M Y", strtotime($r['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reviews yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
