<?php
include '../Model/order_customer.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - Retailflow</title>
    <link rel="stylesheet" href="order_customer.css">
    <link rel="stylesheet" href="customer_dashboard.css">
</head>
<body>

<aside id="sidebar">
    <h3>Customer Dashboard</h3>
    <nav class="sidebar-nav">
        <ul>
            <li><a href="customer_dashboard.php">Dashboard</a></li>
            <li><a href="view_cart.php">My Cart</a></li>
            <li><a class="active" href="order_customer.php">My Orders</a></li>
        </ul>
    </nav>
</aside>

<div id="dashboard-content">
    <div class="top-bar">
        <h1>My Orders</h1>
        <a class="logout-btn" href="../Controller/logout.php">Logout</a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="empty-box">
            <p>No orders found.</p>
            <a href="customer_dashboard.php" class="btn">Continue Shopping</a>
        </div>
    <?php else: ?>

        <?php foreach ($orders as $order): ?>
            <?php
                $items = getOrderItems($conn, (int)$order['id']);
            ?>

            <div class="order-card">
                <div class="order-header">
                    <div>
                        <strong>Order #<?= htmlspecialchars($order['id']) ?></strong>
                        <div class="muted">Date: <?= htmlspecialchars($order['order_date']) ?></div>
                    </div>

                    <div class="order-meta">
                        <div><strong>Total:</strong> BDT <?= htmlspecialchars($order['total_amount']) ?></div>
                        <div><strong>Payment:</strong> <?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?></div>
                        <div class="status status-<?= htmlspecialchars($order['status']) ?>">
                            <?= htmlspecialchars($order['status']) ?>
                        </div>
                    </div>
                </div>

                <div class="order-items">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $it): ?>
                                    <?php
                                        $img = !empty($it['image']) ? "../uploads/" . $it['image'] : "../uploads/default.png";
                                        $subtotal = ((float)$it['price']) * ((int)$it['quantity']);
                                    ?>
                                    <tr>
                                        <td class="p-cell">
                                            <img src="<?= htmlspecialchars($img) ?>" alt="Product">
                                            <span><?= htmlspecialchars($it['title']) ?></span>
                                        </td>
                                        <td><?= (int)$it['quantity'] ?></td>
                                        <td>BDT <?= number_format((float)$it['price'], 2) ?></td>
                                        <td>BDT <?= number_format($subtotal, 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4">No order items found for this order.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>

</body>
</html>

<?php
$db->closeConnection($conn);
?>
