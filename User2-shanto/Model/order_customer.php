<?php
session_start();
include '../Model/DatabaseConnection.php';

// ✅ Login check
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = (int)($user['id'] ?? 0);

// ✅ Optional: role check (only customers)
if (($user['role'] ?? '') !== 'customer') {
    // if admin/seller tries to access, redirect (change as you want)
    header("Location: login.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();

// ✅ Fetch all orders for this customer (based on your table columns)
$stmt = $conn->prepare("
    SELECT id, user_id, total_amount, status, payment_method, order_date
    FROM orders
    WHERE user_id = ?
    ORDER BY order_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();

// ✅ Fetch items for each order (order_items + products)
function getOrderItems($conn, $order_id) {
    $stmt = $conn->prepare("
        SELECT 
            oi.product_id,
            oi.quantity,
            oi.price,
            p.title,
            p.image
        FROM order_items oi
        JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $items = [];
    while ($row = $res->fetch_assoc()) {
        $items[] = $row;
    }
    $stmt->close();
    return $items;
}
?>