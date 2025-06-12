<?php 
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$products = getFilteredProducts($search, $category);
$categories = getAllCategories();

// Get average rating
function getAverageRating($productId) {
    global $conn;
    $stmt = $conn->prepare("SELECT AVG(rating) as avg FROM product_reviews WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return round($result['avg'] ?? 0, 1);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>MyShop - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .hero {
            position: relative;
            background-image: url('images/shopping2.jpg');
            background-size: cover;
            background-position: center;
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }
        .hero-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 2rem;
            width: 100%;
            height: 100%;
            border-radius: 12px;
            display: flex;
            align-items: center;
        }
        .hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
            max-width: 700px;
            margin: 0;
        }
        .sidebar {
            background-color: #fff;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .product-card {
            background: #fff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 1rem;
            transition: transform 0.2s ease-in-out;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-img {
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
        h6 {
            font-weight: 600;
        }
        .star {
            color: gold;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container">
    <div class="hero">
        <div class="hero-overlay">
            <h1>
                Welcome to <span style="color: #0d6efd;">MyShop</span><br>
                <span style="font-size: 1.1rem; font-weight: 400; color: #f8f9fa;">
                    Find the best products across all categories with unbeatable deals.
                </span>
            </h1>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="sidebar">
                <h5>Search</h5>
                <form method="GET" class="mb-4">
                    <input type="text" name="search" class="form-control mb-2" placeholder="Search products..." value="<?= htmlspecialchars($search); ?>">
                    <button class="btn btn-success w-100">Search</button>
                </form>

                <h5>Categories</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="d-block py-1 <?= $category == '' ? 'fw-bold' : ''; ?>">All</a></li>
                    <?php foreach ($categories as $cat): ?>
                        <li><a href="?category=<?= $cat['id']; ?>" class="d-block py-1 <?= $category == $cat['id'] ? 'fw-bold' : ''; ?>"><?= $cat['name']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="row">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <?php $avgRating = getAverageRating($product['id']); ?>
                        <div class="col-md-4 mb-4">
                            <div class="product-card">
                                <a href="product.php?id=<?= $product['id']; ?>" style="text-decoration: none; color: inherit;">
                                    <img src="images/<?= $product['image']; ?>" class="w-100 product-img mb-2">
                                    <h6><?= htmlspecialchars($product['name']); ?></h6>
                                    <p class="mb-1">₹<?= number_format($product['price']); ?></p>
                                    <div>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star"><?= $i <= $avgRating ? '★' : '☆'; ?></span>
                                        <?php endfor; ?>
                                        <small>(<?= $avgRating ?>)</small>
                                    </div>
                                </a>
                                <a href="cart.php?action=add&id=<?= $product['id']; ?>" class="btn btn-sm btn-primary mt-2 w-100">Add to Cart</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No products found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Firebase SDK -->
<script src="https://www.gstatic.com/firebasejs/10.0.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.0.0/firebase-messaging-compat.js"></script>
<script>
const firebaseConfig = {
  apiKey: "AIzaSyBHoHqIHSYXyZpcsS5Gw6HGa5bYK-ktpFw",
  authDomain: "ecommerce-store-52bb1.firebaseapp.com",
  projectId: "ecommerce-store-52bb1",
  storageBucket: "ecommerce-store-52bb1.appspot.com",
  messagingSenderId: "81418308198",
  appId: "1:81418308198:web:885689707e54ecbd613ba7",
  measurementId: "G-THBCZS5BCH"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('firebase-messaging-sw.js')
    .then(registration => {
      return Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
          return messaging.getToken({
            vapidKey: 'BIEIZYBbCptX0-r6YVWqWc1swFk_1lsosv5NgMjWrcu73PeB-Vb7w87cRRtDgPMMNk1dQSpXkmnTTuepo8bejU8',
            serviceWorkerRegistration: registration
          });
        }
      });
    })
    .then(token => {
      return fetch('save-token.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ token })
      });
    })
    .catch(err => {
      console.error('Error with Firebase messaging:', err);
    });
}

messaging.onMessage(function(payload) {
  const { title, body } = payload.notification;
  new Notification(title, { body, icon: '/ecommerce-project/assets/icon.png' });
});
</script>
</body>
</html>
