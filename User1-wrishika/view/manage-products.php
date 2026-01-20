<?php
$page_title = "Manage Products";
include 'header.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . '/../Model/Database.php';
require_once __DIR__ . '/../Model/Product.php';

$db = new Database();
$product_model = new Product($db);
$products = $product_model->getAllProducts();
?>

<div style="margin-bottom: 2rem;">
    <h1>Manage Products</h1>
    <a href="index.php?page=admin-dashboard" class="btn secondary" style="text-decoration: none;">← Back to Dashboard</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Seller</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td><?php echo htmlspecialchars($product['title']); ?></td>
                    <td><?php echo htmlspecialchars($product['seller_name'] ?? 'Unknown'); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td>৳<?php echo number_format($product['price'], 2); ?></td>
                    <td>
                        <?php
                        if ($product['stock'] > 10) {
                            echo '<span style="color: green;">✓ ' . $product['stock'] . '</span>';
                        } elseif ($product['stock'] > 0) {
                            echo '<span style="color: orange;">⚠ ' . $product['stock'] . '</span>';
                        } else {
                            echo '<span style="color: red;">✗ 0</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="index.php?page=product-detail&id=<?php echo $product['id']; ?>" class="btn" style="padding: 0.5rem 1rem; text-decoration: none; font-size: 13px;">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
