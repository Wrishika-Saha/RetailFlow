<?php
session_start();

if (!($_SESSION["isLoggedIn"] ?? false)) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$seller_id = $user['id'];
$name = $user['name'] ?? 'Seller';

include(__DIR__ . '/../Model/DatabaseConnection.php');
$db = new DatabaseConnection();
$conn = $db->openConnection();

$products = [];
$sql = "SELECT * FROM products WHERE seller_id = '$seller_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$db->closeConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Products</title>
    <link rel="stylesheet" href="sellerdashboard.css">
</head>
<body>

<h1>My Products</h1>
<p>Seller: <?php echo htmlspecialchars($name); ?></p>

<a class="btn" href="sellerdashboard.php">← Back to Dashboard</a>
<a class="btn" href="addproduct.php">+ Add Product</a>

<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Description</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>

    <?php if (!empty($products)) { foreach ($products as $p) { ?>
        <tr>
            <td><?= $p['id']; ?></td>
            <td><?= $p['title']; ?></td>
            <td><?= $p['price']; ?></td>
            <td><?= $p['stock']; ?></td>
            <td><?= $p['description']; ?></td>
            <td>
                <img src="../uploads/<?= $p['image']; ?>" width="60">
            </td>
            <td>
                <a class="btn" href="s_editproduct.php?id=<?= $p['id']; ?>">Edit</a>
                <a class="btn btn-red" href="deleteproduct.php?id=<?= $p['id']; ?>">Delete</a>
            </td>
        </tr>
    <?php } } else { ?>
        <tr>
            <td colspan="7">No products found</td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
