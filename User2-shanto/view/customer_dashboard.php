<?php
session_start();
$user = $_SESSION['user'] ?? null;
$name = $user['name'] ?? 'Customer';
$profile = $user['profile_picture'] ?? 'default.png';
$searchQuery = $_POST['search'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard - Market Mingle</title>
    <link rel="stylesheet" href="customer_dashboard.css">
</head>
<body>
<aside id="sidebar">
    <h3>Customer Dashboard</h3>
    <nav class="sidebar-nav">
        <ul>
            <li><a href="customer_dashboard.php">Dashboard</a></li>
            <li><a href="view_cart.php">My Cart</a></li>
            <li><a href="orders_list.php">My Orders</a></li>
            <li><a href="settings.php">Settings</a></li>
        </ul>
    </nav>
</aside>


<div id="dashboard-content">

    <div class="user-info">
        <img src="../uploads/<?php echo htmlspecialchars($profile); ?>" alt="Profile">
        <span>Welcome, <?php echo htmlspecialchars($name); ?></span>
        <a class="logout-btn" href="../Controller/logout.php">Logout</a>
    </div>

    <h2>All Products</h2>

    <form method="POST" class="search-form">
        <input type="text" name="search" placeholder="Search products"
               value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit">Search</button>
    </form>

    <a href="view_cart.php" class="view-cart-btn">View Cart</a>

    
    <div class="product-grid">
        <?php
        if (!empty($products)) {
            foreach ($products as $product) {
                $imageSrc = $product['image']
                    ? '../uploads/' . $product['image']
                    : '../uploads/default.png';

                echo "
                <div class='product-card'>
                    <img src='{$imageSrc}' alt='{$product['title']}'>
                    <h3>{$product['title']}</h3>
                    <p class='price'>BDT {$product['price']}</p>
                    <p>{$product['description']}</p>
                    <a href='product_detail.php?id={$product['id']}' class='view-details-btn'>
                        View Details
                    </a>
                </div>";
            }
        } else {
            echo "<p>No products available.</p>";
        }
        ?>
    </div>

</div>

</body>
</html>
