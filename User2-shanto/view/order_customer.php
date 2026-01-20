<?php
session_start();
include '../Model/DatabaseConnection.php';


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = (int)($user['id'] ?? 0);


if (($user['role'] ?? '') !== 'customer') {
    
    header("Location: login.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();


$stmt = $conn->prepare("
    SELECT id, user_id, total_amount, status, payment_method, order_date
    FROM orders
    WHERE user_id = ?
    ORDER BY order_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();


function getOrderItems($conn, $order_id) {
    $stmt = $conn->prepare("
        SELECT 
            oi.product_id,
            oi.quantity,
            oi.price,
            p.title,
            p.image
        FROM order_items oi
        JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $items = [];
    while ($row = $res->fetch_assoc()) {
        $items[] = $row;
    }
    $stmt->close();
    return $items;
}
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
