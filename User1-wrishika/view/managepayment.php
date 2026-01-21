<?php
include '../Model/manage_payments.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments - Admin</title>
    
    <link rel="stylesheet" href="manage_payments.css">  
</head>
<body>
    <a href="logout.php" class="logout-btn">Logout</a>

  
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

    <section id="manage-payments">
        <div class="container">
            <h1>Manage Payments</h1>

            <table border="1" cellpadding="8" cellspacing="0">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($payments)) {
                        foreach ($payments as $payment) {
                            echo "<tr>
                                <td>{$payment['order_id']}</td>
                                <td>{$payment['payment_method']}</td>
                                <td>{$payment['status']}</td>
                                <td>BDT {$payment['amount']}</td>
                                <td>
                                    <a class='btn-delete' href='../Model/manage_payments.php?delete_id={$payment['id']}' onclick=\"return confirm('Are you sure?')\">Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No payments available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>
