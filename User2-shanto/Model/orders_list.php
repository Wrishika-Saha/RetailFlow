<?php
session_start();
include '../Model/DatabaseConnection.php';

$db = new DatabaseConnection();
$conn = $db->openConnection();

/* ✅ Admin only */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../View/login.php");
    exit();
}

/* =====================
   ✅ DELETE ORDER
   delete order_items first, then orders
===================== */
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../View/orders_list.php?deleted=1");
    exit();
}

/* =====================
   ✅ UPDATE ORDER STATUS
===================== */
if (isset($_POST['update_status'])) {
    $order_id = (int)($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? 'pending';

    $allowed = ['pending', 'completed', 'shipped'];
    if (!in_array($status, $allowed)) {
        $status = 'pending';
    }

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../View/orders_list.php?updated=1");
    exit();
}

/* =====================
   ✅ FETCH ORDERS + ITEMS + PRODUCT NAME
   This returns multiple rows for same order if multiple items exist
===================== */
$sql = "
SELECT 
    o.id AS order_id,
    o.user_id,
    o.total_amount,
    o.payment_method,
    o.status,
    o.order_date,
    oi.product_id,
    oi.quantity,
    oi.price AS item_price,
    p.title AS product_name
FROM orders o
LEFT JOIN order_items oi ON o.id = oi.order_id
LEFT JOIN products p ON oi.product_id = p.id
ORDER BY o.order_date DESC
";

$result = $conn->query($sql);

$orders = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$db->closeConnection($conn);
?>
