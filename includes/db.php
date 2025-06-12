<?php
$conn = new mysqli("localhost", "root", "", "ecommerce");
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}
?>
