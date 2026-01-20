<?php
$page_title = "Shopping Cart";
include 'header.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php?page=login");
    exit;
}

require_once __DIR__ . '/../Model/Database.php';
require_once __DIR__ . '/../Model/Cart.php';

$db = new Database();
$cart = new Cart($db);
$cart_items = $cart->getCartItems($_SESSION['user']['id']);
$total = $cart->getCartTotal($_SESSION['user']['id']);
?>

<h1>Shopping Cart</h1>

<?php if (isset($_GET['message'])): ?>
    <div class="alert success"><?php echo htmlspecialchars($_GET['message']); ?></div>
<?php endif; ?>

<?php if (empty($cart_items)): ?>
    <div class="alert" style="background: #e7f3ff; border: 1px solid #b3d9ff; color: #004085;">
        Your cart is empty. <a href="index.php" style="color: #0052cc; font-weight: bold;">Continue shopping</a>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    <img src="Uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                                    <div>
                                        <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                                        <br>
                                        <small style="color: #999;">SKU: <?php echo $item['product_id']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>৳<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="POST" action="../Controller/update-cart-process.php" style="display: flex; gap: 0.5rem; align-items: center;">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" style="width: 60px;">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn" style="padding: 0.5rem 1rem;">Update</button>
                                </form>
                            </td>
                            <td>৳<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <form method="POST" action="../Controller/remove-from-cart-process.php" style="display: inline;">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn danger" onclick="return confirm('Remove item?')">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="col sidebar">
            <div class="card">
                <h3>Order Summary</h3>
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #eee;">
                    <span>Subtotal:</span>
                    <span>৳<?php echo number_format($total, 2); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #eee;">
                    <span>Shipping:</span>
                    <span>৳0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold; margin-bottom: 1rem;">
                    <span>Total:</span>
                    <span>৳<?php echo number_format($total, 2); ?></span>
                </div>

                <a href="checkout.php" class="btn" style="display: block; width: 100%; text-align: center; padding: 1rem; text-decoration: none; margin-bottom: 1rem;">Proceed to Checkout</a>
                <a href="index.php" class="btn secondary" style="display: block; width: 100%; text-align: center; padding: 1rem; text-decoration: none;">Continue Shopping</a>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>
