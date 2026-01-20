<?php
include 'DatabaseConnection.php'; 


$db = new DatabaseConnection();
$conn = $db->openConnection();
$seller_id = (int)($_SESSION['user']['id']); 


$products = [];
$productSql = "SELECT * FROM products WHERE seller_id = '$seller_id' ORDER BY created_at DESC";
$productResult = $conn->query($productSql);
if ($productResult && $productResult->num_rows > 0) {
    while ($row = $productResult->fetch_assoc()) {
        $products[] = $row;
    }
}


$vouchers = [];
$voucherSql = "SELECT * FROM vouchers WHERE seller_id = '$seller_id' ORDER BY created_at DESC";
$voucherResult = $conn->query($voucherSql);
if ($voucherResult && $voucherResult->num_rows > 0) {
    while ($row = $voucherResult->fetch_assoc()) {
        $vouchers[] = $row;
    }
}

$db->closeConnection($conn);
?>

