<!-- sellerdashboard.php -->
<?php
include('../Model/products_list.php'); // Fetch seller products
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="sellerdash.css">
</head>
<body>
    <?php include('header.php'); ?> <!-- Optional header -->

    <h1>Seller Dashboard</h1>
    <a class="btn" href="addproduct.php">+ Add Product</a>

    <div class="card">
        <h2>Your Products</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($products) {
                foreach ($products as $p) {
                    $imgSrc = isset($p['image']) ? 'data:image/jpeg;base64,' . base64_encode($p['image']) : '';
                    echo "<tr>
                            <td>" . htmlspecialchars($p['id']) . "</td>
                            <td>" . htmlspecialchars($p['title']) . "</td>
                            <td>" . htmlspecialchars($p['price']) . "</td>
                            <td>" . htmlspecialchars($p['stock']) . "</td>
                            <td>" . htmlspecialchars($p['description']) . "</td>
                            <td>" . ($imgSrc ? "<img src='{$imgSrc}' alt='" . htmlspecialchars($p['title']) . "' width='100'>" : 'No image') . "</td>
                            <td>
                                <a class='btn' href='editproduct.php?id=" . htmlspecialchars($p['id']) . "'>Edit</a>
                                <a class='btn btn-red' href='deleteproduct.php?id=" . htmlspecialchars($p['id']) . "'>Delete</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No products available</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <?php include('footer.php'); ?> <!-- Optional footer -->

</body>
</html>
