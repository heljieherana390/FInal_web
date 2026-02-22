<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_name    = $_SESSION['admin_username'] ?? 'Admin';
$product_count = $conn->query("SELECT id FROM products")->num_rows;
$order_count   = $conn->query("SELECT product_id FROM orders")->num_rows;
$user_count    = $conn->query("SELECT id FROM users")->num_rows;

// Revenue (sum of totals from delivered orders)
$revenue_res = $conn->query("SELECT SUM(total) as revenue FROM orders WHERE status = 'Delivered'");
$revenue_row = $revenue_res->fetch_assoc();
$revenue     = $revenue_row['revenue'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Herana Pastry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-layout">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="pas.webp" alt="Logo">
            <h1>Herana<br>Pastry</h1>
        </div>
        <nav>
            <a href="dashboard.php" class="active">
                <span class="sidebar-icon">🏠</span>
                <span>Overview</span>
            </a>
            <a href="products.php">
                <span class="sidebar-icon">🎂</span>
                <span>Manage Products</span>
            </a>
            <a href="orders.php">
                <span class="sidebar-icon">📦</span>
                <span>View Orders</span>
            </a>
            <a href="users.php">
                <span class="sidebar-icon">👥</span>
                <span>Customer List</span>
            </a>
        </nav>
        <div class="sidebar-logout">
            <a href="admin_logout.php">
                <span class="sidebar-icon">🚪</span>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <div class="page-header">
            <div class="page-header-text">
                <h2>Welcome back, <?= htmlspecialchars($admin_name) ?>! 👋</h2>
                <p>Here's what's happening with your pastry shop today.</p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Pastries</h3>
                <div class="number"><?= $product_count ?></div>
                <p>Items in your catalog</p>
            </div>
            <div class="stat-card">
                <h3>Total Orders</h3>
                <div class="number"><?= $order_count ?></div>
                <p>Orders received to date</p>
            </div>
            <div class="stat-card">
                <h3>Customers</h3>
                <div class="number"><?= $user_count ?></div>
                <p>Registered sweet lovers</p>
            </div>
            <div class="stat-card">
                <h3>Revenue</h3>
                <div class="number" style="font-size:2rem;">₱<?= number_format($revenue, 0) ?></div>
                <p>From delivered orders</p>
            </div>
        </div>

    </div>

</body>
</html>