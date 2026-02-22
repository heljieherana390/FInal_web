<?php
ob_start();
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

if (isset($_POST['add'])) {
    $name     = trim($_POST['name']);
    $price    = floatval($_POST['price']);
    $category = $_POST['category'];

    if (empty($name) || empty($price) || empty($category)) {
        $message = "All fields are required.";
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
        $message = "Please upload a product image.";
    } else {
        $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            $message = "Invalid file type. Allowed: jpg, jpeg, png, gif, webp.";
        } else {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $imageName = time() . '_' . uniqid() . '.' . $ext;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName)) {
                $stmt = $conn->prepare("INSERT INTO products (name, price, category, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sdss", $name, $price, $category, $imageName);

                if ($stmt->execute()) {
                    header("Location: products.php?success=1");
                    exit();
                } else {
                    $message = "Database error: " . $conn->error;
                }
                $stmt->close();
            } else {
                $message = "Failed to upload image. Check folder permissions.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Pastry | Herana Pastry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="form-page">

<div class="card">
    <h2>🎂 Add New Pastry</h2>

    <?php if ($message): ?>
        <div class="error-msg"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Pastry Name</label>
        <input type="text" name="name" placeholder="e.g. Chocolate Lava Cake" required
               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

        <label>Category</label>
        <select name="category" required>
            <option value="">-- Select Category --</option>
            <option value="Cakes"     <?= (($_POST['category'] ?? '') == 'Cakes')     ? 'selected' : '' ?>>🎂 Cakes</option>
            <option value="Cupcakes"  <?= (($_POST['category'] ?? '') == 'Cupcakes')  ? 'selected' : '' ?>>🧁 Cupcakes</option>
            <option value="Pastries"  <?= (($_POST['category'] ?? '') == 'Pastries')  ? 'selected' : '' ?>>🥐 Other Pastries</option>
        </select>

        <label>Price (₱)</label>
        <input type="number" step="0.01" min="0" name="price" placeholder="0.00" required
               value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">

        <label>Product Image</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit" name="add">Upload to Shop</button>
    </form>

    <a href="products.php" class="back-link">← Back to Products</a>
</div>

</body>
</html>