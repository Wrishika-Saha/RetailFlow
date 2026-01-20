<?php
session_start();
include '../Model/Database.php'; 
include '../Model/sellerdashboard.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$seller_id = (int)($user['id'] ?? 0);
$name = $user['name'] ?? 'Seller';
$profile = $user['profile_picture'] ?? 'default.png';
$profilePath = "../uploads/" . $profile;
if (!file_exists($profilePath)) $profilePath = "../uploads/default.png";




?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Seller Dashboard</title>
<link rel="stylesheet" href="sellerdashboard.css">


</head>
<body>
<header class="seller-header">
    <div class="header-left">
        <h1 class="logo">
            <span class="brand">RetailFlow</span>
        </h1>
    </div>

    <div class="header-right">
        <div class="seller-profile">
            <img src="<?= htmlspecialchars($profilePath); ?>" alt="Profile">
            <span><?= htmlspecialchars($name); ?></span>
        </div>
        <a href="../Controller/logout.php" class="logout-btn">Logout</a>
    </div>
</header>


<h1>Seller Dashboard</h1>

<div class="btn-group">
    <a class="btn" href="addproduct.php">+ Add Product</a>
    <a class="btn" href="addvoucher.php">+ Add Voucher</a>
    <button class="btn" onclick="toggleSection('products')">Your Products</button>
    <button class="btn" onclick="toggleSection('vouchers')">Your Vouchers</button>
</div>

<div class="card" id="products" style="display:block;">
    <h2>Your Products</h2>
    <table>
        <tr>
            <th>ID</th><th>Title</th><th>Price</th><th>Stock</th><th>Description</th><th>Image</th><th>Actions</th>
        </tr>
        <?php if(!empty($products)) { foreach($products as $p){ ?>
        <tr>
            <td><?= $p['id']; ?></td>

            <td><?= $p['title']; ?></td>

            <td><?= $p['price']; ?></td>

            <td><?= $p['stock']; ?></td>

            <td><?= $p['description']; ?></td>
            
            <td><img src="../uploads/<?= $p['image']; ?>" width="60"></td>
            <td>
                <a class="btn" href="editproductseller.php?id=<?= $p['id']; ?>">Edit</a>
                <a class="btn btn-red" href="../Model/deleteproduct.php?id=<?= $p['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php } } else { ?>
        <tr><td colspan="7">No products found</td></tr>
        <?php } ?>
    </table>
</div>

<div class="card" id="vouchers" style="display:none;">
    <h2>Your Vouchers</h2>
    <table>
        <tr>
            <th>Code</th><th>Discount</th><th>Expiry</th><th>Actions</th>
        </tr>
        <?php if(!empty($vouchers)) { foreach($vouchers as $v){ ?>
        <tr>
            <td><?= $v['code']; ?></td>

            <td><?= $v['discount']; ?>%</td>

            <td><?= $v['expiry_date']; ?></td>
            <td>
                <a class="btn" href="editvoucherseller.php?id=<?= $v['id']; ?>">Edit</a>
                <a class="btn btn-red" href="../Model/deletevoucher.php?id=<?= $v['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php } } else { ?>
        <tr><td colspan="4">No vouchers found</td></tr>
        <?php } ?>
    </table>
</div>

<script>
function toggleSection(id){
    document.getElementById('products').style.display='none';

    document.getElementById('vouchers').style.display='none';

    document.getElementById(id).style.display='block';
}
</script>
</body>
</html>
