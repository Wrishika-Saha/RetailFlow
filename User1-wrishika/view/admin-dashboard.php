<?php
$page_title = "Admin Dashboard";
include 'header.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . '/../Model/Database.php';

$db = new Database();
$conn = $db->connect();

// Get statistics
$users_result = $conn->query("SELECT COUNT(*) as count FROM users");
$total_users = $users_result->fetch_assoc()['count'];

$sellers_result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'seller'");
$total_sellers = $sellers_result->fetch_assoc()['count'];

$products_result = $conn->query("SELECT COUNT(*) as count FROM products");
$total_products = $products_result->fetch_assoc()['count'];

$orders_result = $conn->query("SELECT COUNT(*) as count FROM orders");
$total_orders = $orders_result->fetch_assoc()['count'];

$revenue_result = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status IN ('processing', 'shipped', 'delivered')");
$total_revenue = $revenue_result->fetch_assoc()['total'] ?? 0;

// Get recent orders
$recent_orders = $conn->query("SELECT o.*, u.name, u.email FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");
$orders = $recent_orders->fetch_all(MYSQLI_ASSOC);
?>

<div style="margin-bottom: 2rem;">
    <h1>Admin Dashboard ⚙️</h1>
    <p style="color: #666; font-size: 16px;">System Administration Panel</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="card" style="text-align: center;">
        <p style="font-size: 12px; color: #999; margin-bottom: 0.5rem;">TOTAL USERS</p>
        <p style="font-size: 28px; font-weight: bold; color: #667eea;"><?php echo $total_users; ?></p>
    </div>
    <div class="card" style="text-align: center;">
        <p style="font-size: 12px; color: #999; margin-bottom: 0.5rem;">SELLERS</p>
        <p style="font-size: 28px; font-weight: bold; color: #ff6b6b;"><?php echo $total_sellers; ?></p>
    </div>
    <div class="card" style="text-align: center;">
        <p style="font-size: 12px; color: #999; margin-bottom: 0.5rem;">PRODUCTS</p>
        <p style="font-size: 28px; font-weight: bold; color: #ffc107;"><?php echo $total_products; ?></p>
    </div>
    <div class="card" style="text-align: center;">
        <p style="font-size: 12px; color: #999; margin-bottom: 0.5rem;">ORDERS</p>
        <p style="font-size: 28px; font-weight: bold; color: #17a2b8;"><?php echo $total_orders; ?></p>
    </div>
    <div class="card" style="text-align: center;">
        <p style="font-size: 12px; color: #999; margin-bottom: 0.5rem;">TOTAL REVENUE</p>
        <p style="font-size: 28px; font-weight: bold; color: #28a745;">৳<?php echo number_format($total_revenue, 2); ?></p>
    </div>
</div>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
    <a href="index.php?page=manage-users" class="btn">👥 Manage Users</a>
    <a href="index.php?page=manage-products" class="btn secondary">📦 Manage Products</a>
    <a href="index.php?page=manage-orders" class="btn secondary">📋 Manage Orders</a>
    <a href="index.php?page=manage-payments" class="btn secondary">💳 Manage Payments</a>
</div>

<div class="card">
    <h2>Recent Orders</h2>
    
    <?php if (empty($orders)): ?>
        <div style="text-align: center; padding: 2rem; color: #999;">
            <p>No orders yet.</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong>#<?php echo $order['id']; ?></strong></td>
                        <td>
                            <?php echo htmlspecialchars($order['name']); ?><br>
                            <small style="color: #999;"><?php echo htmlspecialchars($order['email']); ?></small>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
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
                        <td><a href="index.php?page=order-detail&id=<?php echo $order['id']; ?>" class="btn" style="padding: 0.5rem 1rem; text-decoration: none; font-size: 13px;">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
