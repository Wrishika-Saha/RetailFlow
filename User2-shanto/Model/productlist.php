<?php
session_start();

// Include database connection
include '../Model/DatabaseConnection.php';
$db = new DatabaseConnection();
$conn = $db->openConnection();

// Fetch products for the logged-in seller
$seller_id = $_SESSION['user_id']; // Make sure user is logged in

$stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ?");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$stmt->close();
$db->closeConnection($conn);

// Now $products contains all products for the logged-in seller
?>
