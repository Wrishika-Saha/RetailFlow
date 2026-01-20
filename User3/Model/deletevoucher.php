<?php
session_start();

include '../Model/DatabaseConnection.php';


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}


if (!isset($_GET['id'])) {
    echo "<script>alert('No voucher selected'); window.location='sellerdashboard.php';</script>";
    exit();
}

$voucher_id = $_GET['id'];
$seller_id  = $_SESSION['user']['id'];

$db = new DatabaseConnection();
$conn = $db->openConnection();


$stmt = $conn->prepare("DELETE FROM vouchers WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $voucher_id, $seller_id);

if ($stmt->execute()) {
    echo "<script>alert('Voucher deleted successfully!'); window.location='../View/sellerdashboard.php';</script>";
} else {
    echo "Error deleting voucher: " . $stmt->error;
}

$stmt->close();
$db->closeConnection($conn);
?>

