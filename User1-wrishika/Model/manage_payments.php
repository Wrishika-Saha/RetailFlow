<?php
include 'DatabaseConnection.php';

$db = new DatabaseConnection();
$conn = $db->openConnection();


$result = $conn->query("SELECT * FROM payments ORDER BY id ASC");

$payments = [];
while ($row = $result->fetch_assoc()) {
    $payments[] = $row;
}


if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM payments WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: ../View/managepayment.php"); 
    exit;
}

$db->closeConnection($conn);
?>
