<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Herana Pastry</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="navbar-brand">Herana Pastry</span>
    <div class="nav-links">
        <a href="dashboard.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="view_orders.php">Orders</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</nav>

<div class="shop-hero">
   
    <h2>Fresh Pastries</h2>
    <p>Choose your favourite and add it to your cart</p>
</div>

<div class="container-shop">
    <div class="product-grid">
        <?php
        $query = "SELECT * FROM products ORDER BY id DESC";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
        ?>
        <div class="product-card">
            <div class="img-container">
                <img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
            </div>
            <div class="product-info">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <span class="price">₱<?php echo number_format($row['price'], 2); ?></span>
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-primary btn-full">Add to Cart</button>
                </form>
            </div>
        </div>
        <?php
            endwhile;
        else:
            echo "<p class='no-products'>No products available yet.</p>";
        endif;
        ?>
    </div>
</div>

</body>
</html>