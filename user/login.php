<?php 
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'contact' => $user['contact'],
            'address' => $user['address']
        ];
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .form-control {
            font-size: 0.9rem;
            padding: 0.6rem;
        }
        .btn-success {
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
    <div class="login-container">
        <h4 class="text-center mb-4">Login to Your Account</h4>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input name="email" type="email" class="form-control mb-3" placeholder="Email" required>
            <input name="password" type="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" class="btn btn-success">Login</button>
        </form>

        <div class="form-footer">
            <small>
                New user? <a href="register.php">Register here</a>
            </small>
        </div>
    </div>
</div>

</body>
</html>
