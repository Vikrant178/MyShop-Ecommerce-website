<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Email already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, contact, address, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $contact, $address, $password);
        if ($stmt->execute()) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user'] = [
                'id' => $stmt->insert_id,
                'name' => $name,
                'email' => $email,
                'contact' => $contact,
                'address' => $address
            ];
            header("Location: ../index.php");
            exit;
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 450px;
            margin: 60px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .form-control {
            font-size: 0.9rem;
            padding: 0.6rem;
        }
        .btn-primary {
            width: 100%;
        }
        .form-footer {
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="register-container">
        <h4 class="text-center mb-4">Create Your Account</h4>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input name="name" type="text" class="form-control mb-3" placeholder="Full Name" required>
            <input name="email" type="email" class="form-control mb-3" placeholder="Email" required>
            <input name="contact" type="text" class="form-control mb-3" placeholder="Contact Number" required>
            <input name="address" type="text" class="form-control mb-3" placeholder="Address" required>
            <input name="password" type="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <div class="form-footer">
            <small>Already have an account? <a href="login.php">Login here</a></small>
        </div>
    </div>
</div>

</body>
</html>
