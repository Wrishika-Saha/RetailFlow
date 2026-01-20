<?php
session_start();

include '../Model/DatabaseConnection.php';


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}


if (!isset($_GET['id'])) {
    echo "<script>alert('Product ID not specified'); window.location='manage_products.php';</script>";
    exit();
}

$product_id = $_GET['id'];

$db = new DatabaseConnection();
$conn = $db->openConnection();


$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    echo "<script>alert('Product deleted successfully!'); window.location='../View/sellerdashboard.php';</script>";
} else {
    echo "Error deleting product: " . $stmt->error;
}

$stmt->close();
$db->closeConnection($conn);
?>

