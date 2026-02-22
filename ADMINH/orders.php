<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$sql = "SELECT 
            orders.product_id,
            orders.items,
            orders.product_name,
            orders.price,
            orders.quantity,
            orders.total,
            orders.status,
            users.username AS customer
        FROM orders
        JOIN users ON orders.user_id = users.id
        ORDER BY orders.product_id DESC";

$orders = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders | Herana Pastry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-layout">

    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="pas.webp" alt="Logo">
            <h1>Herana<br>Pastry</h1>
        </div>
        <nav>
            <a href="dashboard.php"><span class="sidebar-icon">🏠</span><span>Overview</span></a>
            <a href="products.php"><span class="sidebar-icon">🎂</span><span>Manage Products</span></a>
            <a href="orders.php" class="active"><span class="sidebar-icon">📦</span><span>View Orders</span></a>
            <a href="users.php"><span class="sidebar-icon">👥</span><span>Customer List</span></a>
        </nav>
        <div class="sidebar-logout">
            <a href="admin_logout.php"><span class="sidebar-icon">🚪</span><span>Logout</span></a>
        </div>
    </div>

    <div class="main-content">

        <div class="page-header">
            <div class="page-header-text">
                <h2>Customer Orders</h2>
                <p>Monitor and update delivery statuses.</p>
            </div>
        </div>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div class="error-msg" style="background:#edfbf3;color:#27ae60;border-color:#b7eacb;">
                ✅ Order status updated successfully!
            </div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer & Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($orders && $orders->num_rows > 0): ?>
                        <?php while ($row = $orders->fetch_assoc()):
                            $item_desc    = !empty($row['items']) ? $row['items'] : $row['product_name'] . " (x" . $row['quantity'] . ")";
                            $display_total = ($row['total'] > 0) ? $row['total'] : ($row['price'] * $row['quantity']);
                        ?>
                        <tr>
                            <td><strong style="color:var(--terracotta);">#<?= $row['product_id'] ?></strong></td>
                            <td>
                                <strong><?= htmlspecialchars($row['customer'] ?? 'Unknown') ?></strong><br>
                                <small style="color:#999"><?= htmlspecialchars($item_desc) ?></small>
                            </td>
                            <td><strong>₱<?= number_format($display_total, 2) ?></strong></td>
                            <td>
                                <span class="status-pill <?= htmlspecialchars($row['status'] ?? 'Pending') ?>">
                                    <?= htmlspecialchars($row['status'] ?? 'Pending') ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="update_status.php" class="order-form">
                                    <input type="hidden" name="order_id" value="<?= $row['product_id'] ?>">
                                    <select name="new_status">
                                        <option value="Pending"    <?= ($row['status'] ?? '') == 'Pending'    ? 'selected' : '' ?>>Pending</option>
                                        <option value="Processing" <?= ($row['status'] ?? '') == 'Processing' ? 'selected' : '' ?>>Processing</option>
                                        <option value="Delivered"  <?= ($row['status'] ?? '') == 'Delivered'  ? 'selected' : '' ?>>Delivered</option>
                                        <option value="Cancelled"  <?= ($row['status'] ?? '') == 'Cancelled'  ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn-update">Save</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-data">
                                <p>📦 No orders found yet.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>