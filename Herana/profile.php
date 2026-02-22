<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "herana_pastry");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']); $stmt->execute();
$result = $stmt->get_result(); $user = $result->fetch_assoc();
$stmt->close(); $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Herana Pastry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="page-body">

<nav class="navbar">
    <a href="dashboard.php" class="navbar-brand">Herana Pastry</a>
    <div class="nav-links">
        <a href="dashboard.php">Shop</a>
        <a href="view_orders.php">My Cart</a>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</nav>

<div class="profile-page">
    <div class="profile-card">
        <div class="profile-header">
            <img src="pas.webp" alt="Herana Pastry" class="shop-logo">
            <div class="user-avatar">🧁</div>
            <h2>My Profile</h2>
            <p class="subtitle">Boutique Account</p>
        </div>
        <div class="profile-body">
            <div class="info-group">
                <span>Username</span>
                <strong><?php echo htmlspecialchars($user['username']); ?></strong>
            </div>
            <div class="info-group">
                <span>Email Address</span>
                <strong><?php echo htmlspecialchars($user['email']); ?></strong>
            </div>
            <div class="profile-actions">
                <a href="edit_profile.php" class="btn btn-primary">Edit Account Details</a>
                <a href="logout.php" class="btn btn-danger">Sign Out</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>