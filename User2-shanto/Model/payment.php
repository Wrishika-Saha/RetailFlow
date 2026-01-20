<?php
session_start();
include('../Model/DatabaseConnection.php');

// Check if user is logged in
$user_id = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Get order ID from session
$order_id = $_SESSION['order_id'] ?? null;
if (!$order_id) {
    echo "<script>alert('No order found!'); window.location='customer_dashboard.php';</script>";
    exit();
}

// Fetch order details
$db = new DatabaseConnection();
$conn = $db->openConnection();
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();
$db->closeConnection($conn);

if (!$order) {
    echo "<script>alert('Order not found!'); window.location='customer_dashboard.php';</script>";
    exit();
}
?>
