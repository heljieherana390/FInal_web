<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id   = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: products.php?deleted=1");
    } else {
        header("Location: products.php?error=1");
    }
    $stmt->close();
} else {
    header("Location: products.php");
}

$conn->close();
exit();
?>