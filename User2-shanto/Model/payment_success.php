<?php

session_start();
include '../Model/DatabaseConnection.php';


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../View/login.php");
    exit();
}

$user_id = (int) $_SESSION['user']['id'];


$order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;
if ($order_id <= 0) {
    header("Location: ../View/customer_dashboard.php");
    exit();
}

$db = new DatabaseConnection();
$conn = $db->openConnection();


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
   
    $db->closeConnection($conn);
    header("Location: ../View/customer_dashboard.php");
    exit();
}

$amount = $order['total_amount'];
$payment_method = 'Online'; 


$check = $conn->prepare("SELECT id FROM payments WHERE order_id = ?");
$check->bind_param("i", $order_id);
$check->execute();
$paymentResult = $check->get_result();
$existingPayment = $paymentResult->fetch_assoc();
$check->close();

if ($existingPayment) {
    
    $pay = $conn->prepare(
        "UPDATE payments 
         SET status = 'Completed', payment_method = ?, amount = ?
         WHERE order_id = ?"
    );
    $pay->bind_param("sdi", $payment_method, $amount, $order_id);
} else {
    
    $pay = $conn->prepare(
        "INSERT INTO payments (order_id, payment_method, status, amount)
         VALUES (?, ?, 'Completed', ?)"
    );
    $pay->bind_param("isd", $order_id, $payment_method, $amount);
}

$pay->execute();
$pay->close();

$updateOrder = $conn->prepare(
    "UPDATE orders SET status = 'completed' WHERE id = ?"
);
$updateOrder->bind_param("i", $order_id);
$updateOrder->execute();
$updateOrder->close();

$db->closeConnection($conn);


?>
