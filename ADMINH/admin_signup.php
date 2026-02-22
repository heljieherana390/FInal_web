<?php
session_start();
require 'db_connection.php';

$error   = "";
$success = "";

if (isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if (empty($username) || empty($password) || empty($confirm)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $check = $conn->prepare("SELECT id FROM admins WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Username already taken. Please choose another.";
        } else {
            $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {
                // ✅ Auto redirect to login after 2 seconds
                $success = "Account created! Redirecting to login...";
            } else {
                $error = "Something went wrong. Please try again.";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up | Herana Pastry</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <?php if ($success): ?>
    <!-- Auto redirect to login after 2 seconds on success -->
    <meta http-equiv="refresh" content="2;url=admin_login.php">
    <?php endif; ?>
</head>
<body class="login-page">

<div class="login-container">
    <div class="logo">
        <img src="pas.webp" alt="Herana Pastry Logo">
    </div>

    <h2>Create Account</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success-msg">
            ✅ <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit" name="signup">Create Account</button>
    </form>

    <a href="admin_login.php" class="back-link">Already have an account? Login</a>
    <?php endif; ?>
</div>

</body>
</html>