<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    header("Location: user/login.php");
    exit;
}

$name = $_SESSION['user']['name'] ?? '';
$email = $_SESSION['user']['email'] ?? '';
$contact = $_SESSION['user']['contact'] ?? '';
$address = $_SESSION['user']['address'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        .card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #86b7fe;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-6">
        <div class="card p-4 shadow-sm rounded-4">
            <h3 class="mb-4 text-center">Checkout</h3>
            <form action="place-order.php" method="POST" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required pattern="[A-Za-z\s]+" value="<?= htmlspecialchars($name); ?>" placeholder="Enter your name">
                    <div class="invalid-feedback">Please enter a valid name (letters only).</div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($email); ?>" placeholder="example@email.com">
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Contact Number <span class="text-danger">*</span></label>
                    <input type="tel" id="phone" name="phone" class="form-control" required pattern="^[6-9]\d{9}$" value="<?= htmlspecialchars($contact); ?>" placeholder="10-digit mobile number">
                    <div class="invalid-feedback">Enter a valid 10-digit Indian mobile number starting with 6-9.</div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                    <textarea id="address" name="address" class="form-control" rows="4" required><?= htmlspecialchars($address); ?></textarea>
                    <div class="invalid-feedback">Address is required.</div>
                </div>

                <div class="mb-3">
                     <label class="form-label"><strong>Payment Method:</strong></label><br>
                     <div class="form-check">
                     <input class="form-check-input" type="radio" name="payment_method" value="COD" id="cod" checked required>
                    <label class="form-check-label" for="cod">Cash on Delivery</label>
                </div>
                <div class="form-check">
                     <input class="form-check-input" type="radio" name="payment_method" value="Online Payment" id="online" required>
                    <label class="form-check-label" for="online">Online Payment</label>
                 </div>
</div>



                <button type="submit" class="btn btn-primary w-100">Place Order</button>
            </form>
        </div>
    </div>
</div>

<script>
    (() => {
        'use strict';
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

</body>
</html>
