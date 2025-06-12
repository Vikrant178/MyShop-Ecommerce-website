<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">MyShop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <?php if (!isset($_SESSION['user_logged_in']) && !isset($_SESSION['admin_logged_in'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="user/login.php"><i class="bi bi-person"></i> User Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/login.php"><i class="bi bi-person-gear"></i> Admin Login</a>
                    </li>
                <?php else: ?>
                    <?php if (isset($_SESSION['user_logged_in'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="user/my-orders.php"><i class="bi bi-bag-check"></i> My Orders</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div> 
    </div>
</nav>
