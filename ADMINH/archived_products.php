<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$sql    = "SELECT * FROM archived_products ORDER BY archived_at DESC";
$result = $conn->query($sql);

$archived = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $archived[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Products | Herana Pastry</title>
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
            <a href="archived_products.php" class="active"><span class="sidebar-icon">🗄️</span><span>Archived</span></a>
            <a href="orders.php"><span class="sidebar-icon">📦</span><span>View Orders</span></a>
            <a href="users.php"><span class="sidebar-icon">👥</span><span>Customer List</span></a>
        </nav>
        <div class="sidebar-logout">
            <a href="admin_logout.php"><span class="sidebar-icon">🚪</span><span>Logout</span></a>
        </div>
    </div>

    <div class="main-content">

        <div class="page-header">
            <div class="page-header-text">
                <h2>🗄️ Archived Products</h2>
                <p>Products removed from the shop. Restore or permanently delete them.</p>
            </div>
            <a href="products.php" class="btn btn-primary">← Back to Products</a>
        </div>

        <?php if (isset($_GET['restored'])): ?>
            <div class="error-msg" style="background:#edfbf3;color:#27ae60;border-color:#b7eacb;">
                ✅ Product restored to the shop successfully!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="error-msg" style="background:#fff0ee;color:#c0392b;border-color:#ffdcdc;">
                🗑️ Product permanently deleted.
            </div>
        <?php endif; ?>

        <?php if (!empty($archived)): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Archived On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($archived as $row): ?>
                        <tr>
                            <td>
                                <img src="../uploads/<?= htmlspecialchars($row['image']) ?>"
                                     onerror="this.src='https://via.placeholder.com/56x56?text=🎂'"
                                     class="prod-img" alt="product">
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($row['name']) ?></strong>
                                <br><small style="color:var(--muted);">ID #<?= $row['id'] ?></small>
                            </td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td>₱<?= number_format($row['price'], 2) ?></td>
                            <td class="date-text">
                                <?= date("M d, Y — g:i A", strtotime($row['archived_at'])) ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="restore_product.php?id=<?= $row['id'] ?>"
                                       class="restore"
                                       onclick="return confirm('Restore this product to the shop?')">
                                        ♻️ Restore
                                    </a>
                                    <a href="permanent_delete.php?id=<?= $row['id'] ?>"
                                       class="delete"
                                       onclick="return confirm('Permanently delete this product? This cannot be undone.')">
                                        🗑️ Delete Forever
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="table-container no-data">
                <p>🗄️ No archived products. Archived items will appear here.</p>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>