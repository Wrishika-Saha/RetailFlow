<?php
session_start();
include('../Model/DatabaseConnection.php');

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;
if (!$isLoggedIn) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$name = $user['name'];
$profile = $user['profile_picture'] ?? 'default.png';

$db = new DatabaseConnection();
$conn = $db->openConnection();

$userCountResult = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$totalUsers = $userCountResult->fetch_assoc()['total_users'];

$productCountResult = $conn->query("SELECT COUNT(*) AS total_products FROM products");
$totalProducts = $productCountResult->fetch_assoc()['total_products'];

$db->closeConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - RetailFlow</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

<div class="dashboard-container">

    <aside id="sidebar">
        <h3>Admin Dashboard</h3>
        <nav class="sidebar-nav">
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

    <div id="dashboard-content">

        <div class="user-info">
            <img src="../uploads/<?php echo htmlspecialchars($profile); ?>">
            <span>Welcome, <?php echo htmlspecialchars($name); ?></span>
            <a class="logout-btn" href="../Controller/logout.php">Logout</a>
        </div>

        <section id="dashboard">
            <h2>Overview</h2>

            <div class="dashboard-cards">
                <div class="card">
                    <h4>Total Products</h4>
                    <p><?php echo $totalProducts; ?></p>
                    <a href="manageproduct.php">View</a>
                </div>

                <div class="card">
                    <h4>Total Users</h4>
                    <p><?php echo $totalUsers; ?></p>
                    <a href="manage_users.php">View</a>
                </div>
            </div>
        </section>

    </div>
</div>

</body>
</html>
