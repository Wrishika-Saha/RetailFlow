<?php
session_start();

include '../Model/DatabaseConnection.php';

$db = new DatabaseConnection();
$conn = $db->openConnection();

// Check product ID
if (!isset($_GET['id'])) {
    echo "Product ID not specified.";
    exit();
}

$product_id = (int) $_GET['id'];

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();

$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "Product not found.";
    exit();
}

$db->closeConnection($conn);
?>
