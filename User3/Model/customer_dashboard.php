<?php
include 'DatabaseConnection.php'; // Include your existing DB class

$db = new DatabaseConnection();
$conn = $db->openConnection();

// Optional: handle search
$searchQuery = $_POST['search'] ?? '';

if ($searchQuery) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE title LIKE ? OR category LIKE ?");
    $likeQuery = "%$searchQuery%";
    $stmt->bind_param("ss", $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$db->closeConnection($conn);
?>
