<?php
session_start();

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
if (!$user_id) {
    echo "<script>alert('You must log in first.'); window.location='../View/login.php';</script>";
    exit();
}

// Check cart
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty.'); window.location='../View/index.php';</script>";
    exit();
}

include '../Model/DatabaseConnection.php';
$db = new DatabaseConnection();
$conn = $db->openConnection();

// Calculate total
$totalPrice = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// Insert order
$status = 'pending';
$stmt = $conn->prepare(
    "INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, ?)"
);
$stmt->bind_param("ids", $user_id, $totalPrice, $status); // <-- use $user_id
$stmt->execute();

// Get order ID
$order_id = $conn->insert_id;
$_SESSION['order_id'] = $order_id;

// Insert order items
foreach ($_SESSION['cart'] as $item) {
    $stmt_item = $conn->prepare(
        "INSERT INTO order_items (order_id, product_id, quantity, price)
         VALUES (?, ?, ?, ?)"
    );
    $stmt_item->bind_param(
        "iiid",
        $order_id,
        $item['product_id'],
        $item['quantity'],
        $item['price']
    );
    $stmt_item->execute();
    $stmt_item->close();
}

// Clear cart
unset($_SESSION['cart']);

$db->closeConnection($conn);

// Go to payment page
header("Location: ../View/payment.php");
exit();
?>
