<?php
$page_title = "Order Confirmed";
include 'header.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php?page=login");
    exit;
}

require_once __DIR__ . '/../Model/Database.php';
require_once __DIR__ . '/../Model/Order.php';

if (!isset($_GET['order_id'])) {
    header("Location: ../index.php");
    exit;
}

$db = new Database();
$order_model = new Order($db);
$order = $order_model->getOrderById($_GET['order_id']);

if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
    header("Location: orders.php");
    exit;
}

$order_items = $order_model->getOrderDetails($_GET['order_id']);
?>

<div style="max-width: 600px; margin: 2rem auto;">
    <div class="card" style="text-align: center;">
        <div style="font-size: 48px; margin-bottom: 1rem;">✓</div>
        <h1 style="color: #28a745;">Order Placed Successfully!</h1>
        <p style="font-size: 18px; color: #666; margin-bottom: 2rem;">Thank you for your order</p>

        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 4px; margin-bottom: 2rem; text-align: left;">
            <p><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
            <p><strong>Order Date:</strong> <?php echo date('F j, Y H:i', strtotime($order['created_at'])); ?></p>
            <p><strong>Status:</strong> <span style="background: #fff3cd; padding: 0.25rem 0.75rem; border-radius: 4px; color: #856404;">Pending</span></p>
            <p><strong>Total Amount:</strong> ৳<?php echo number_format($order['total_amount'], 2); ?></p>
        </div>

        <h3>Order Items</h3>
        <table style="margin-bottom: 2rem;">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>৳<?php echo number_format($item['price'], 2); ?></td>
                        <td>৳<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p style="color: #666; font-size: 14px; margin-bottom: 2rem;">
            A confirmation email has been sent to <strong><?php echo htmlspecialchars($order['email']); ?></strong>
        </p>

        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="orders.php" class="btn">View My Orders</a>
            <a href="index.php" class="btn secondary">Continue Shopping</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
