<?php
include '../Model/manageproduct.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Admin</title>
    
    <link rel="stylesheet" href="manage_product.css">
</head>
<body>

    <a href="../Controller/logout.php">Logout</a>

    
    <aside>
        <h3>Admin Dashboard</h3>
        <nav>
            <ul>
                <li><a href="Admindashboard.php">Dashboard</a></li>
                <li><a href="manageproduct.php">Manage Products</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="orders_list.php">Manage Orders</a></li>
                <li><a href="managepayment.php">Manage Payments</a></li>
                <li><a href="settings.php">Settings</a></li>
            </ul>
        </nav>
    </aside>

    
    <section>
        <h1>Manage Products</h1>

        
        <form method="POST">
            <input type="text" name="search" placeholder="Search by title or category" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </form>

        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($products)) {
                    foreach ($products as $product) {
                        $imageSrc = "../uploads/default.png";
if (!empty($product['image'])) {
    $possiblePath = "../uploads/" . $product['image'];
    if (file_exists($possiblePath)) {
        $imageSrc = $possiblePath;
    }
}


                        echo "<tr>
                            <td>{$product['id']}</td>
                            <td>{$product['title']}</td>
                            <td>{$product['category']}</td>
                            <td>{$product['price']}</td>
                            <td>{$product['stock']}</td>
                            <td>{$product['description']}</td>
                            <td><img src='{$imageSrc}' width='80' height='80'></td>
                            <td>
                                <a href='editproduct.php?id={$product['id']}' class='btn-edit'>Edit</a> |
                                <a href='manageproduct.php?delete_id={$product['id']}' class='btn-delete' onclick=\"return confirm('Are you sure?')\">Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No products found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

</body>
</html>
