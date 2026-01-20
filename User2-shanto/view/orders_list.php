<?php
include('../Model/orders_list.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Admin</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="orders_list.css">
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

<section id="manage-orders">
    <div class="container">
        <h1>Manage Orders</h1>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Item Price</th>
                    <th>Total Amount</th>
                    <th>Payment</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= (int)$order['order_id'] ?></td>
                            <td><?= (int)$order['user_id'] ?></td>

                            <td><?= htmlspecialchars($order['product_name'] ?? 'N/A') ?></td>
                            <td><?= (int)($order['quantity'] ?? 0) ?></td>
                            <td>BDT <?= number_format((float)($order['item_price'] ?? 0), 2) ?></td>

                            <td>BDT <?= number_format((float)$order['total_amount'], 2) ?></td>
                            <td><?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($order['order_date']) ?></td>

                            <td>
                                <form method="POST" action="orders_list.php">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="pending"   <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="shipped"   <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                    </select>
                                    <input type="hidden" name="order_id" value="<?= (int)$order['order_id'] ?>">
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>

                            <td>
                                <a class="btn-delete"
                                   href="orders_list.php?delete_id=<?= (int)$order['order_id'] ?>"
                                   onclick="return confirm('Are you sure you want to delete this order?')">
                                   Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">No orders available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

</body>
</html>
