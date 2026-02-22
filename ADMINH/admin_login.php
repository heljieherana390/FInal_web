<?php
session_start();
require 'db_connection.php';

$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        $_SESSION['admin_id']       = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Incorrect username or password.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Herana Pastry</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">

<div class="login-container">
    <div class="logo">
        <img src="pas.webp" alt="Herana Pastry Logo">
    </div>

    <h2>Welcome Back</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required autocomplete="username">
        <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
        <button type="submit" name="login">Login</button>
    </form>

    <a href="admin_signup.php" class="back-link">Don't have an account? Sign Up</a>
</div>

</body>
</html>