<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$user_id = $_SESSION['user_id'];
$message = "";

if (isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];
    $sql = "DELETE FROM orders WHERE product_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql); $stmt->bind_param("ii", $order_id, $user_id);
    if ($stmt->execute()) { $message = "Item removed from your cart."; }
}
if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id']; $quantity = $_POST['quantity'];
    $sql = "UPDATE orders SET quantity = ? WHERE product_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql); $stmt->bind_param("iii", $quantity, $order_id, $user_id);
    $stmt->execute();
}
if (isset($_POST['confirm_checkout'])) {
    $sql = "UPDATE orders SET status = 'Pending' WHERE user_id = ? AND status = 'In Cart'";
    $stmt = $conn->prepare($sql); $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) { $message = "Order placed successfully! 🍰"; }
}

$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql); $stmt->bind_param("i", $user_id);
$stmt->execute(); $result = $stmt->get_result();
$total = 0; $has_cart = false; $orders_list = [];
while ($row = $result->fetch_assoc()) {
    $orders_list[] = $row;
    if ($row['status'] == 'In Cart') { $total += ($row['price'] * $row['quantity']); $has_cart = true; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | Herana Pastry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="page-body">

<nav class="navbar">
    <a href="dashboard.php" class="navbar-brand">Herana Pastry</a>
    <div class="nav-links">
        <a href="dashboard.php">Shop</a>
        <a href="view_orders.php">My Orders</a>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</nav>

<div class="orders-container">
    <div class="orders-main">
        <h2>Your Selection</h2>
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (count($orders_list) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($orders_list as $row):
                $status_class = ($row['status'] == 'In Cart') ? 'status-in-cart' : 'status-pending';
            ?>
            <tr>
                <td>
                    <strong><?php echo htmlspecialchars($row['product_name']); ?></strong><br>
                    <small style="color:var(--muted)"><?php echo date("M d, Y", strtotime($row['created_at'])); ?></small>
                </td>
                <td>₱<?php echo number_format($row['price'], 2); ?></td>
                <td>
                    <form method="POST" style="display:flex;gap:6px;align-items:center;">
                        <input type="hidden" name="order_id" value="<?php echo $row['product_id']; ?>">
                        <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1" class="qty-input">
                        <button name="update_order" class="btn-row-update">Update</button>
                    </form>
                </td>
                <td><span class="status-pill <?php echo $status_class; ?>"><?php echo $row['status']; ?></span></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Remove item?');">
                        <input type="hidden" name="order_id" value="<?php echo $row['product_id']; ?>">
                        <button name="delete_order" class="btn-row-delete">Remove</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div style="text-align:center;padding:50px 20px;color:var(--muted);">
                <p style="font-size:15px;margin-bottom:16px;">Your cart is empty.</p>
                <a href="dashboard.php" class="btn btn-primary">Back to Bakery</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="summary-card">
        <h3>Order Summary</h3>
        <div class="summary-row"><span>Cart Subtotal</span><span>₱<?php echo number_format($total, 2); ?></span></div>
        <div class="summary-row"><span>Shipping</span><span style="color:var(--success-text);">FREE</span></div>
        <hr class="summary-divider">
        <div class="summary-row">
            <strong>Total</strong>
            <strong class="summary-total">₱<?php echo number_format($total, 2); ?></strong>
        </div>
        <form method="POST">
            <?php if ($has_cart): ?>
                <button name="confirm_checkout" class="btn-checkout">Complete Checkout</button>
            <?php else: ?>
                <button class="btn-checkout" disabled>No Items in Cart</button>
            <?php endif; ?>
        </form>
    </div>
</div>

</body>
</html>