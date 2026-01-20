<?php
include('../Model/view_cart.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart - RetailFlow</title>
    <link rel="stylesheet" href="view_cart.css">
</head>
<body>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="customer_dashboard.php" class="back-btn">Back</a>

    <section id="cart">
        <div class="container">
            <h1>Your Cart</h1>

            <?php if (!empty($_SESSION['cart'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalPrice = 0;
                        foreach ($_SESSION['cart'] as $item):
                            $productTotal = $item['price'] * $item['quantity'];
                            $totalPrice += $productTotal;
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['title']); ?></td>
                                <td>BDT <?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo intval($item['quantity']); ?></td>
                                <td>BDT <?php echo number_format($productTotal, 2); ?></td>
                                <td>
                                    <a class="btn-delete" href="view_cart.php?delete_id=<?php echo $item['product_id']; ?>" 
                                       onclick="return confirm('Are you sure you want to remove this item?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="total">
                    <h3>Total: BDT <?php echo number_format($totalPrice, 2); ?></h3>
                </div>
                <form action="../Model/place_order.php" method="POST">
                     <button type="submit" class="btn">Place Order</button>
                  </form>

            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
