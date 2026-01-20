<?php
session_start();  

include '../Model/DatabaseConnection.php';
$db = new DatabaseConnection();
$conn = $db->openConnection();


if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']); 

 
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    
    if (!$product) {
        echo "Product not found.";
        $db->closeConnection($conn);
        exit();
    }
} else {
    echo "Product ID not specified.";
    $db->closeConnection($conn);
    exit();
}



$db->closeConnection($conn);
?>
