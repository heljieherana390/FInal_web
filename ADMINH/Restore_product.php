<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch from archive
    $stmt = $conn->prepare("SELECT * FROM archived_products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($product) {
        // Re-insert into products
        $ins = $conn->prepare(
            "INSERT INTO products (id, name, price, category, image) VALUES (?, ?, ?, ?, ?)"
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
            // Remove from archive
            $del = $conn->prepare("DELETE FROM archived_products WHERE id = ?");
            $del->bind_param("i", $id);
            $del->execute();
            $del->close();

            header("Location: archived_products.php?restored=1");
        } else {
            header("Location: archived_products.php?error=1");
        }
        $ins->close();
    } else {
        header("Location: archived_products.php");
    }
} else {
    header("Location: archived_products.php");
}

$conn->close();
exit();
?>