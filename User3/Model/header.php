<!-- includes/header.php -->
<?php session_start(); ?>
<head>
    <link rel="stylesheet" href="header.css"> <!-- link CSS -->
</head>
<header>
    <div id="logo">
        <a href="index.php">Market Mingle</a>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- User is logged in -->
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin_dashboard.php">Dashboard</a></li>
                <?php elseif ($_SESSION['role'] === 'seller'): ?>
                    <li><a href="sellerdashboard.php">Dashboard</a></li>
                <?php else: ?>
                    <li><a href="customer_dashboard.php">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <!-- User is not logged in -->
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>

        </ul>
    </nav>
</header>
