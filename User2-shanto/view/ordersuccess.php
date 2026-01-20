<?php
session_start();
include('../Model/db.php'); 


if (!isset($_SESSION['user_id'])) {
    header('Location: ../View/login.php');
    exit();
}

if (!isset($_SESSION['order_id'])) {
    echo "<script>alert('Order not found.'); window.location='../View/index.php';</script>";
    exit();
}

$order_id = $_SESSION['order_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - RetailFlow</title>
    <link rel="stylesheet" href="order_success.css"> 
</head>
<body>
    <?php include('header.php'); ?>

    <section id="order-success">
        <div class="container">
            <h1>Thank You for Your Order!</h1>
            <p>Your order has been placed successfully. You will receive a confirmation email shortly.</p>
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>

            <a href="customer_dashboard.php" class="btn-back">Back to Dashboard</a>
        </div>
    </section>

    <?php include('footer.php'); ?> 
</body>
</html>
