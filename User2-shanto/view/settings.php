<?php
include('../Model/settings.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Settings</title>
    <link rel="stylesheet" href="settings.css">
</head>
<body>

<a href="../Controller/logout.php" class="logout-btn">Logout</a>


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

<section id="settings">
    <div class="container">
        <h1>Admin Settings</h1>

        <?php if (isset($_GET['saved'])): ?>
            <div class="alert-success">✅ Settings saved (cookies updated).</div>
        <?php endif; ?>

        <form method="POST" action="settings.php">
            <div class="form-group">
                <label for="site_name">Site Name</label>
                <input type="text" id="site_name" name="site_name"
                       value="<?= htmlspecialchars($siteName) ?>" required>
            </div>

            <div class="form-group">
                <label for="currency">Currency</label>
                <input type="text" id="currency" name="currency"
                       value="<?= htmlspecialchars($currency) ?>" required>
            </div>

            <div class="form-group">
                <label for="theme">Theme</label>
                <input type="text" id="theme" name="theme"
                       value="<?= htmlspecialchars($theme) ?>" required>
            </div>

            <button type="submit" class="btn-save">Save Changes</button>
        </form>
    </div>
</section>

<?php include('footer.php'); ?>
</body>
</html>
