<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$sql    = "SELECT * FROM products ORDER BY category ASC, name ASC";
$result = $conn->query($sql);

$categories = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[$row['category']][] = $row;
    }
}

// Count archived for badge
$archived_count = $conn->query("SELECT COUNT(*) as cnt FROM archived_products")->fetch_assoc()['cnt'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products | Herana Pastry</title>
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
            <a href="products.php" class="active"><span class="sidebar-icon">🎂</span><span>Manage Products</span></a>
            <a href="archived_products.php">
                <span class="sidebar-icon">🗄️</span>
                <span>Archived
                    <?php if ($archived_count > 0): ?>
                        <span class="archive-badge"><?= $archived_count ?></span>
                    <?php endif; ?>
                </span>
            </a>
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
                <h2>Pastry Inventory</h2>
                <p>Manage your shop items and categories.</p>
            </div>
            <a href="add_product.php" class="btn btn-primary">+ Add New Pastry</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="error-msg" style="background:#edfbf3;color:#27ae60;border-color:#b7eacb;">
                ✅ Product added successfully!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['archived'])): ?>
            <div class="error-msg" style="background:#f5f0ff;color:#7c3aed;border-color:#d8b4fe;">
                🗄️ Product archived successfully.
                <a href="archived_products.php" style="color:#7c3aed;font-weight:700;margin-left:8px;">View Archive →</a>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-msg" style="background:#fff0ee;color:#c0392b;border-color:#ffdcdc;">
                ❌ Something went wrong. Please try again.
            </div>
        <?php endif; ?>

        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $groupName => $prods): ?>
                <div class="category-heading">
                    <?= $groupName === 'Cakes' ? '🎂' : ($groupName === 'Cupcakes' ? '🧁' : '🥐') ?>
                    <?= htmlspecialchars($groupName) ?>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prods as $row): ?>
                            <tr>
                                <td>
                                    <img src="../uploads/<?= htmlspecialchars($row['image']) ?>"
                                         onerror="this.src='https://via.placeholder.com/56x56?text=🎂'"
                                         class="prod-img" alt="product">
                                </td>
                                <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                                <td>₱<?= number_format($row['price'], 2) ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="edit_product.php?id=<?= $row['id'] ?>" class="edit">✏️ Edit</a>
                                        <a href="archive_product.php?id=<?= $row['id'] ?>" class="delete"
                                           onclick="return confirm('Delete this product? You can restore it later from the Archive.')">🗑️ Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="table-container no-data">
                <p>🍰 No products yet. Start by adding one!</p>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>