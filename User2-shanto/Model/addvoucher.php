<?php
session_start(); // Start session

include '../Model/DatabaseConnection.php'; 

$db = new DatabaseConnection();
$conn = $db->openConnection();

// Make sure seller is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    $discount = $_POST['discount'] ?? 0;
    $expiry_date = $_POST['expiry_date'] ?? '';

    // Validate inputs
    if (empty($code) || empty($discount) || empty($expiry_date)) {
        echo "<script>alert('All fields are required'); window.history.back();</script>";
        exit();
    }

    // Prepare statement to insert voucher
    $stmt = $conn->prepare("INSERT INTO vouchers (code, discount, expiry_date, seller_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sisi", $code, $discount, $expiry_date, $seller_id);

    if ($stmt->execute()) {
        echo "<script>alert('Voucher added successfully'); window.location='sellerdashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
}

$db->closeConnection($conn);
?>
