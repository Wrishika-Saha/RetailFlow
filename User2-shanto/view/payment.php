<?php
include('../Model/payment.php'); 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - RetailFlow</title>
    <link rel="stylesheet" href="payment.css">
</head>
<body>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="customer_dashboard.php" class="back-btn">Cancel</a>

    <section id="payment">
        <div class="container">
            <h1>Payment for Order #<?php echo htmlspecialchars($order['id']); ?></h1>
            <p>Total Price: BDT <?php echo number_format($order['total_amount'], 2); ?></p>

            <form action="../Model/process_payment.php" method="POST">
                <h3>Select Payment Method</h3>
                <select name="payment_method" required>
                    <option value="">--Select Payment Method--</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="Bkash">Bkash</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>

                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                <button type="submit" class="btn-pay">Proceed to Payment</button>
            </form>
        </div>
    </section>
</body>
</html>