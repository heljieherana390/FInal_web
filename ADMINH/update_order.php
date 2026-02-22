<?php
session_start();
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['new_status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    
    if ($stmt->execute()) {
        header("Location: orders.php?success=1");
    } else {
        echo "Error updating order.";
    }
}
?>