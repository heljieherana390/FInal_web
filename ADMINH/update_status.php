<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $order_id   = intval($_POST['order_id']);
    $new_status = $_POST['new_status'];

    $allowed_statuses = ['Pending', 'Processing', 'Delivered', 'Cancelled'];
    if (!in_array($new_status, $allowed_statuses)) {
        header("Location: orders.php?msg=error");
        exit();
    }

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE product_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        header("Location: orders.php?msg=updated");
    } else {
        header("Location: orders.php?msg=error");
    }
    $stmt->close();
} else {
    header("Location: orders.php");
}
exit();
?>