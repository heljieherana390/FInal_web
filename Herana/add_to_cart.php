<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['product_id'])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// 1. GET THE PRICE AND NAME FROM THE PRODUCTS TABLE
$get_product = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
$get_product->bind_param("i", $product_id);
$get_product->execute();
$result = $get_product->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    $name = $product['name'];
    $price = $product['price'];

    // 2. CHECK IF ITEM IS ALREADY IN CART (Optional but recommended)
    $check_cart = $conn->prepare("SELECT product_id FROM orders WHERE user_id = ? AND product_name = ? AND status = 'In Cart'");
    $check_cart->bind_param("is", $user_id, $name);
    $check_cart->execute();
    $cart_result = $check_cart->get_result();

    if ($cart_result->num_rows > 0) {
        // If it exists, just increase quantity
        $update_qty = $conn->prepare("UPDATE orders SET quantity = quantity + 1 WHERE user_id = ? AND product_name = ? AND status = 'In Cart'");
        $update_qty->bind_param("is", $user_id, $name);
        $update_qty->execute();
    } else {
        // 3. INSERT NEW ROW WITH THE PRICE RETRIEVED
        // Note: Check your 'orders' table for 'product_id' vs 'id' as we discussed before
        $insert = $conn->prepare("INSERT INTO orders (user_id, product_name, price, quantity, status) VALUES (?, ?, ?, 1, 'In Cart')");
        $insert->bind_param("isd", $user_id, $name, $price);
        $insert->execute();
    }
    
    header("Location: view_orders.php?success=added");
} else {
    header("Location: dashboard.php?error=notfound");
}
exit();
?>