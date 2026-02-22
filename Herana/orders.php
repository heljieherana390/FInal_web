<?php
session_start();
include 'db_connect.php'; // Using your connection file for consistency

// Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// DELETE FUNCTIONALITY
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    if ($stmt->execute()) {
        header('location: view_orders.php'); 
        exit();
    }
}

// UPDATE FUNCTIONALITY
if (isset($_POST['update_btn'])) {
    $id = $_POST['update_id'];
    $qty = $_POST['update_quantity'];
    $stmt = $conn->prepare("UPDATE orders SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $qty, $id, $user_id);
    if ($stmt->execute()) {
        header('location: view_orders.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Crème Haven</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-accent: #c84b2c;
            --secondary-accent: #f39c12;
            --bg-color: #fffaf9;
            --text-main: #4a3f3f;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            padding-top: 100px;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            width: 100%;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .navbar a {
            color: var(--text-main);
            text-decoration: none;
            margin-left: 25px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* ===== MAIN CONTAINER ===== */
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: var(--primary-accent);
            margin-bottom: 30px;
            text-align: center;
        }

        /* ===== TABLE STYLING ===== */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            border-bottom: 2px solid #f2d1c9;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #887a7a;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
        }

        td {
            padding: 20px 15px;
            border-bottom: 1px solid #f9f3f3;
            vertical-align: middle;
        }

        tr:hover td {
            background-color: #fffcfb;
        }

        .product-name {
            font-weight: 600;
            color: var(--text-main);
        }

        /* ===== INPUTS & BUTTONS ===== */
        input[type="number"] {
            width: 60px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            font-family: inherit;
        }

        .btn-update {
            background: var(--secondary-accent);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
            font-size: 12px;
        }

        .btn-update:hover {
            background: #e67e22;
            transform: scale(1.05);
        }

        .btn-delete {
            color: #ff4d4d;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-delete:hover {
            color: #b30000;
            text-decoration: underline;
        }

        /* ===== SUMMARY SECTION ===== */
        .cart-summary {
            margin-top: 30px;
            text-align: right;
            padding-top: 20px;
            border-top: 2px solid #f2d1c9;
        }

        .total-label {
            font-size: 18px;
            color: #887a7a;
        }

        .total-amount {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-accent);
            margin-left: 10px;
        }

        .checkout-btn {
            display: inline-block;
            margin-top: 20px;
            background: var(--primary-accent);
            color: white;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 30px;
            font-weight: 600;
            transition: 0.3s;
        }

        .checkout-btn:hover {
            background: #a63a20;
            box-shadow: 0 5px 15px rgba(200, 75, 44, 0.3);
        }

        @media (max-width: 600px) {
            .container { padding: 20px; width: 95%; }
            table thead { display: none; }
            table td { display: block; text-align: right; padding: 10px 5px; }
            table td::before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                color: #887a7a;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div style="font-family: 'Playfair Display', serif; color: var(--primary-accent); font-weight: bold;">CRÈME HAVEN</div>
        <div class="nav-links">
            <a href="dashboard.php">Shop</a>
            <a href="logout.php" style="color: var(--primary-accent);">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1>Your Shopping Cart</h1>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_total = 0;
                $result = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = '$user_id' AND status = 'In Cart'");

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $sub_total = $row['price'] * $row['quantity'];
                        $grand_total += $sub_total;
                ?>
                <tr>
                    <td data-label="Product" class="product-name"><?php echo $row['product_name']; ?></td>
                    <td data-label="Price">₱<?php echo number_format($row['price'], 2); ?></td>

                    <form action="view_orders.php" method="POST">
                        <td data-label="Quantity">
                            <input type="hidden" name="update_id" value="<?php echo $row['id']; ?>">
                            <input type="number" name="update_quantity" value="<?php echo $row['quantity']; ?>" min="1">
                        </td>
                        <td data-label="Action">
                            <button type="submit" name="update_btn" class="btn-update">Update</button>
                        </td>
                    </form>

                    <td data-label="Remove">
                        <a href="view_orders.php?remove=<?php echo $row['id']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Remove this delicious item?');">
                           Delete
                        </a>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; padding: 40px;'>Your cart is empty. <a href='dashboard.php' style='color: var(--primary-accent);'>Go shopping!</a></td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php if ($grand_total > 0): ?>
        <div class="cart-summary">
            <span class="total-label">Grand Total:</span>
            <span class="total-amount">₱<?php echo number_format($grand_total, 2); ?></span>
            <br>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </div>
        <?php endif; ?>
    </div>

</body>
</html>