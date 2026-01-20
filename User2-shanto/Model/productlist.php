<?php
session_start();


include '../Model/DatabaseConnection.php';
$db = new DatabaseConnection();
$conn = $db->openConnection();


$seller_id = $_SESSION['user_id']; 

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


?>
