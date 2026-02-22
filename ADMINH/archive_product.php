<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the product first
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($product) {
        // Insert into archived_products
        $ins = $conn->prepare(
            "INSERT INTO archived_products (id, name, price, category, image, archived_at)
             VALUES (?, ?, ?, ?, ?, NOW())"
        );
        $ins->bind_param(
            "isdss",
            $product['id'],
            $product['name'],
            $product['price'],
            $product['category'],
            $product['image']
        );

        if ($ins->execute()) {
            // Remove from active products
            $del = $conn->prepare("DELETE FROM products WHERE id = ?");
            $del->bind_param("i", $id);
            $del->execute();
            $del->close();

            header("Location: products.php?archived=1");
        } else {
            header("Location: products.php?error=1");
        }
        $ins->close();
    } else {
        header("Location: products.php");
    }
} else {
    header("Location: products.php");
}

$conn->close();
exit();
?>