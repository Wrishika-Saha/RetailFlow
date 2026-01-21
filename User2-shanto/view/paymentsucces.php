<?php
include('../Model/payment_success.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - RetailFlow</title>
    <link rel="stylesheet" href="../View/payment_success.css"> 
</head>
<body>


    <section id="payment-success">
        <div class="container">
            <h1>Payment Successful</h1>
            <p>Your payment has been processed successfully. Thank you for your purchase!</p>
            <p><strong>Order ID:</strong> <?php echo isset($order_id) ? htmlspecialchars($order_id) : ''; ?></p>
            <a href="customer_dashboard.php" class="btn">Back to Dashboard</a>
        </div>
    </section>


</body>
</html>  