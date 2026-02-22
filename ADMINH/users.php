<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List | Herana Pastry</title>
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
            <a href="orders.php"><span class="sidebar-icon">📦</span><span>View Orders</span></a>
            <a href="users.php" class="active"><span class="sidebar-icon">👥</span><span>Customer List</span></a>
        </nav>
        <div class="sidebar-logout">
            <a href="admin_logout.php"><span class="sidebar-icon">🚪</span><span>Logout</span></a>
        </div>
    </div>

    <div class="main-content">

        <div class="page-header">
            <div class="page-header-text">
                <h2>Registered Customers</h2>
                <p> list of all customers who joined in Herana Pastry.</p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email Address</th>
                        <th>Registration Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users && $users->num_rows > 0): ?>
                        <?php while ($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td class="user-id">#<?= $row['id'] ?></td>
                            <td><strong><?= htmlspecialchars($row['username']) ?></strong></td>
                            <td>
                                <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="email-link">
                                    <?= htmlspecialchars($row['email']) ?>
                                </a>
                            </td>
                            <td class="date-text"><?= date("M d, Y", strtotime($row['created_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="no-data">
                                <p>👥 No registered customers yet.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>