<?php 
session_start();

// Login check
$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;
if (!$isLoggedIn) {
    header("Location: login.php");
    exit;
}

// Get user info from session
$user = $_SESSION['user']; // full user array from login
$email = $user['email'];
$name = $user['name'];
$profile = $user['profile_picture'] ?? 'default.png'; // fallback profile image
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
                    <li><a href="admin_dashboard.php">Dashboard</a></li>
                    <li><a href="manageproduct.php">Manage Products</a></li>
                    <li><a href="manage_users.php">Manage Users</a></li>
                    <li><a href="settings.php">Settings</a></li>
                </ul>
            </nav>
        </aside>

        <div id="dashboard-content">
            <div class="user-info">
                <img src="../uploads/<?php echo htmlspecialchars($profile); ?>" alt="Profile Picture">
                <span>Welcome, <?php echo htmlspecialchars($name); ?></span>
                <a class="logout-btn" href="../Controller/logout.php">Logout</a>
            </div>

            <section id="dashboard">
                <h2>Admin Dashboard</h2>

                <div class="dashboard-cards">
                    <div class="card">
                        <h4>Total Products</h4>
                        <p>150</p>
                    </div>

                    <div class="card">
                        <h4>Total Users</h4>
                        <p>1200</p>
                    </div>
                </div>

            </section>
        </div>

    </div>
</body>
</html>
