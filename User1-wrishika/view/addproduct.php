<?php
include '../Model/add_product.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="addproduct.css">
</head>
<body>
    <div class="card">
        <a href="sellerdashboard.php"> << Back to Dashboard</a>
        <h2>Add Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Product Title" required>
            <input type="text" name="category" placeholder="Product Category" required>
            <input type="number" step="0.01" name="price" placeholder="Price (BDT)" required>
            <input type="number" name="stock" placeholder="Stock Quantity" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
