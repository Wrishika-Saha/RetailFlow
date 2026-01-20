<?php
session_start();

include '../Model/DatabaseConnection.php';
$db = new DatabaseConnection();
$conn = $db->openConnection();

if (!isset($_SESSION['order_id'])) {
    echo "<script>alert('Order not found.'); window.location='index.php';</script>";
    exit();
}


$order_id = $_SESSION['order_id'];
$order_details = [];
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $order_details = $result->fetch_assoc();
}
$stmt->close();

$db->closeConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - Market Mingle</title>
    <link rel="stylesheet" href="order_success.css">
</head>
<body>
    <?php include('header.php'); ?> 

    <section id="order-success">
        <h1>Thank You for Your Order!</h1>
        <p>Your order has been placed successfully. You will receive a confirmation email shortly.</p>

        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>

        <?php if (!empty($order_details)) { ?>
            <p><strong>Order Total:</strong> BDT <?php echo htmlspecialchars($order_details['total_amount']); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order_details['payment_method']); ?></p>
        <?php } ?>

        <a href="customer_dashboard.php" class="btn-back">Back to Dashboard</a>
    </section>

    <?php include('footer.php'); ?> <
</body>
</html>
