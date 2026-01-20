<?php
$page_title = "My Orders";
include 'header.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php?page=login");
    exit;
}

require_once __DIR__ . '/../Model/Database.php';
require_once __DIR__ . '/../Model/Order.php';

$db = new Database();
$order_model = new Order($db);
$orders = $order_model->getUserOrders($_SESSION['user']['id']);
?>

<h1>My Orders</h1>

<?php if (empty($orders)): ?>
    <div class="alert" style="background: #e7f3ff; border: 1px solid #b3d9ff; color: #004085;">
        You haven't placed any orders yet. <a href="index.php" style="color: #0052cc; font-weight: bold;">Start shopping now</a>
    </div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo date('F j, Y', strtotime($order['created_at'])); ?></td>
                    <td>৳<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td>
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
                    </td>
                    <td>
                        <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn" style="padding: 0.5rem 1rem; text-decoration: none;">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include 'footer.php'; ?>
