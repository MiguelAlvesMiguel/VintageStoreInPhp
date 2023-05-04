<?php
// purchase.php

include 'dbConnection.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: SignIn.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;

if ($product_id === null) {
    echo "Error: No product selected.";
    exit();
}

// Get product price
$sql_product = "SELECT price FROM Products WHERE product_id = ?";
$stmt_product = mysqli_prepare($conn, $sql_product);
mysqli_stmt_bind_param($stmt_product, 'i', $product_id);
mysqli_stmt_execute($stmt_product);
$result_product = mysqli_stmt_get_result($stmt_product);
$row_product = mysqli_fetch_assoc($result_product);

if (!$row_product) {
    echo "Error: Product not found.";
    exit();
}

$price = $row_product['price'];

// Insert a new transaction
$sql_insert = "INSERT INTO Transactions (buyer_id, product_id, price) VALUES (?, ?, ?)";
$stmt_insert = mysqli_prepare($conn, $sql_insert);
mysqli_stmt_bind_param($stmt_insert, 'iid', $user_id, $product_id, $price);
mysqli_stmt_execute($stmt_insert);

if (mysqli_stmt_affected_rows($stmt_insert) > 0) {
    // Update product availability
    $sql_update = "UPDATE Products SET available = 0 WHERE product_id = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, 'i', $product_id);
    mysqli_stmt_execute($stmt_update);

    echo "Purchase successful! Product ID: {$product_id}";
    
    echo "<script>$('#myModal').modal('toggle')</script>";
    
    echo "<script>window.location = 'index.php';</script>";
} else {
    echo "Error: Purchase failed.";
}

?>