<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id  = intval($_GET['id']);
$res = $conn->prepare("SELECT * FROM products WHERE id = ?");
$res->bind_param("i", $id);
$res->execute();
$product = $res->get_result()->fetch_assoc();

if (!$product) {
    header("Location: products.php");
    exit();
}

$message = "";

if (isset($_POST['update'])) {
    $name     = trim($_POST['name']);
    $price    = floatval($_POST['price']);
    $category = $_POST['category'];

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
        $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            $message = "Invalid file type.";
        } else {
            $imgName = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $imgName);

            $stmt = $conn->prepare("UPDATE products SET name=?, price=?, category=?, image=? WHERE id=?");
            $stmt->bind_param("sdssi", $name, $price, $category, $imgName, $id);
            $stmt->execute();
            header("Location: products.php");
            exit();
        }
    } else {
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, category=? WHERE id=?");
        $stmt->bind_param("sdsi", $name, $price, $category, $id);
        $stmt->execute();
        header("Location: products.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pastry | Herana Pastry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="form-page">

<div class="card">
    <h2>✏️ Edit Pastry</h2>

    <?php if ($message): ?>
        <div class="error-msg"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Pastry Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>Category</label>
        <select name="category" required>
            <option value="Cakes"    <?= $product['category'] == 'Cakes'    ? 'selected' : '' ?>>🎂 Cakes</option>
            <option value="Cupcakes" <?= $product['category'] == 'Cupcakes' ? 'selected' : '' ?>>🧁 Cupcakes</option>
            <option value="Pastries" <?= $product['category'] == 'Pastries' ? 'selected' : '' ?>>🥐 Other Pastries</option>
        </select>

        <label>Price (₱)</label>
        <input type="number" step="0.01" min="0" name="price" value="<?= $product['price'] ?>" required>

        <label>Current Image</label>
        <div style="margin-bottom: 14px;">
            <img src="../uploads/<?= htmlspecialchars($product['image']) ?>"
                 onerror="this.src='https://via.placeholder.com/56x56?text=🎂'"
                 class="current-img" alt="current product image">
        </div>

        <label>Change Image (Optional)</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" name="update">💾 Save Changes</button>
    </form>

    <a href="products.php" class="back-link">← Back to Products</a>
</div>

</body>
</html>