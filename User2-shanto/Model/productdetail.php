<?php
session_start();  // Start session

// Include database connection
include '../Model/DatabaseConnection.php';
$db = new DatabaseConnection();
$conn = $db->openConnection();

// Check if product ID is passed in the URL
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']); // sanitize input

    // Prepare the query to fetch product details by ID
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    // Check if the product exists
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

// You can now use $product array to display details

$db->closeConnection($conn);
?>
