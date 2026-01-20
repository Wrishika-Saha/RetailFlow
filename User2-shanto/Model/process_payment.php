<?php
session_start();
include 'DatabaseConnection.php';

// Check user session and order
$user_id = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
$order_id = $_POST['order_id'] ?? $_SESSION['order_id'] ?? null;
$payment_method = $_POST['payment_method'] ?? null;

if (!$user_id || !$order_id) {
    header("Location: ../View/login.php");
    exit();
}

if (!$payment_method) {
    echo "<script>alert('Please select a payment method.'); window.history.back();</script>";
    exit();
}

// Open DB connection
$db = new DatabaseConnection();
$conn = $db->openConnection();

// Fetch order to verify ownership
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "<script>alert('Order not found!'); window.location='../View/customer_dashboard.php';</script>";
    $db->closeConnection($conn);
    exit();
}

// Insert payment record
$stmt_payment = $conn->prepare("INSERT INTO payments (order_id, payment_method, amount, status) VALUES (?, ?, ?, ?)");
$status = 'completed';
$stmt_payment->bind_param("isds", $order_id, $payment_method, $order['total_amount'], $status);
$stmt_payment->execute();
$stmt_payment->close();

// Update order status
$new_status = 'paid';
$stmt_order = $conn->prepare("UPDATE orders SET status = ?, payment_method = ? WHERE id = ?");
$stmt_order->bind_param("ssi", $new_status, $payment_method, $order_id);
$stmt_order->execute();
$stmt_order->close();

// Clear cart
unset($_SESSION['cart']);

// Close connection
$db->closeConnection($conn);

// Redirect to payment success
header("Location: ../View/paymentsucces.php?order_id=$order_id");
exit();
?>
