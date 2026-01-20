<?php
include '../Model/DatabaseConnection.php';

$db = new DatabaseConnection();
$conn = $db->openConnection();


$result = $conn->query("SELECT * FROM products");
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$db->closeConnection($conn);
?>

