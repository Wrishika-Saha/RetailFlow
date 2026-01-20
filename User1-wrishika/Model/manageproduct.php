<?php
include '../Model/DatabaseConnection.php';

$db = new DatabaseConnection();
$conn = $db->openConnection();


if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manageproduct.php"); 
    exit();
}


$searchQuery = $_POST['search'] ?? '';

if ($searchQuery) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE title LIKE ? OR category LIKE ? ORDER BY id DESC");
    $likeQuery = "%$searchQuery%";
    $stmt->bind_param("ss", $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM products ORDER BY id ASC");
}


$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$db->closeConnection($conn);
?>
