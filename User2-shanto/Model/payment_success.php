<?php
// Model/payment_success.php
session_start();
include '../Model/DatabaseConnection.php';

/* ==========================
   LOGIN CHECK (CUSTOMER)
========================== */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../View/login.php");
    exit();
}

$user_id = (int) $_SESSION['user']['id'];

/* ==========================
   VALIDATE ORDER ID
========================== */
$order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;
if ($order_id <= 0) {
    header("Location: ../View/customer_dashboard.php");
    exit();
}

$db = new DatabaseConnection();
$conn = $db->openConnection();

/* ==========================
   VERIFY ORDER BELONGS TO USER
========================== */
$stmt = $conn->prepare(
    "SELECT id, total_amount, status 
     FROM orders 
     WHERE id = ? AND user_id = ?"
);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    // Order not found or not user's order
    $db->closeConnection($conn);
    header("Location: ../View/customer_dashboard.php");
    exit();
}

$amount = $order['total_amount'];
$payment_method = 'Online'; // or Card / Cash / bKash etc.

/* ==========================
   INSERT / UPDATE PAYMENT
========================== */

// Check if payment already exists for this order
$check = $conn->prepare("SELECT id FROM payments WHERE order_id = ?");
$check->bind_param("i", $order_id);
$check->execute();
$paymentResult = $check->get_result();
$existingPayment = $paymentResult->fetch_assoc();
$check->close();

if ($existingPayment) {
    // Update existing payment
    $pay = $conn->prepare(
        "UPDATE payments 
         SET status = 'Completed', payment_method = ?, amount = ?
         WHERE order_id = ?"
    );
    $pay->bind_param("sdi", $payment_method, $amount, $order_id);
} else {
    // Insert new payment
    $pay = $conn->prepare(
        "INSERT INTO payments (order_id, payment_method, status, amount)
         VALUES (?, ?, 'Completed', ?)"
    );
    $pay->bind_param("isd", $order_id, $payment_method, $amount);
}

$pay->execute();
$pay->close();

/* ==========================
   UPDATE ORDER STATUS
========================== */
$updateOrder = $conn->prepare(
    "UPDATE orders SET status = 'completed' WHERE id = ?"
);
$updateOrder->bind_param("i", $order_id);
$updateOrder->execute();
$updateOrder->close();

$db->closeConnection($conn);

/* ==========================
   $order_id is now available
   for payment_success view
========================== */
?>
