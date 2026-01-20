<?php
$page_title = "Order Details";
include 'header.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php?page=login");
    exit;
}

require_once __DIR__ . '/../Model/Database.php';
require_once __DIR__ . '/../Model/Order.php';
require_once __DIR__ . '/../Model/Payment.php';

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$db = new Database();
$order_model = new Order($db);
$payment_model = new Payment($db);

$order = $order_model->getOrderById($_GET['id']);
if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
    header("Location: orders.php");
    exit;
}

$order_items = $order_model->getOrderDetails($_GET['id']);
$payment = $payment_model->getPaymentByOrder($_GET['id']);
?>

<div class="row">
    <div class="col">
        <div class="card">
            <h1>Order #<?php echo $order['id']; ?></h1>

            <h3>Order Information</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <p><strong>Order Date:</strong><br><?php echo date('F j, Y H:i', strtotime($order['created_at'])); ?></p>
                    <p><strong>Status:</strong><br>
                        <?php
                        $status_colors = [
                            'pending' => '#ffc107',
                            'processing' => '#17a2b8',
                            'shipped' => '#0275d8',
                            'delivered' => '#28a745',
                            'cancelled' => '#dc3545'
                        ];
                        $color = $status_colors[$order['status']] ?? '#6c757d';
                        ?>
                        <span style="background: <?php echo $color; ?>; color: white; padding: 0.25rem 0.75rem; border-radius: 4px;">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </p>
                </div>
                <div>
                    <p><strong>Customer Name:</strong><br><?php echo htmlspecialchars($order['name']); ?></p>
                    <p><strong>Email:</strong><br><?php echo htmlspecialchars($order['email']); ?></p>
                </div>
            </div>

            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $subtotal = 0; foreach ($order_items as $item): ?>
                        <tr>
                            <td>
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    <img src="Uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    <span><?php echo htmlspecialchars($item['title']); ?></span>
                                </div>
                            </td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>৳<?php echo number_format($item['price'], 2); ?></td>
                            <td>৳<?php echo number_format($item['price'] * $item['quantity'], 2); $subtotal += $item['price'] * $item['quantity']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="text-align: right; margin-top: 1rem;">
                <p style="font-size: 18px;"><strong>Total: ৳<?php echo number_format($order['total_amount'], 2); ?></strong></p>
            </div>

            <?php if ($payment): ?>
                <h3>Payment Information</h3>
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px;">
                    <p><strong>Payment Method:</strong> <?php echo ucfirst(str_replace('_', ' ', $payment['payment_method'])); ?></p>
                    <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($payment['transaction_id']); ?></p>
                    <p><strong>Status:</strong> <span style="color: <?php echo $payment['status'] === 'completed' ? '#28a745' : '#ffc107'; ?>;">✓ <?php echo ucfirst($payment['status']); ?></span></p>
                </div>
            <?php endif; ?>

            <a href="orders.php" class="btn secondary" style="margin-top: 2rem; text-decoration: none;">← Back to Orders</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
