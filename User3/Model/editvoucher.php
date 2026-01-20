<?php
session_start();

include '../Model/DatabaseConnection.php';

// Check seller login
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['user']['role'] !== 'seller') {
    header("Location: ../View/login.php");
    exit();
}

$db = new DatabaseConnection();
$conn = $db->openConnection();

// Check voucher ID
if (!isset($_GET['id'])) {
    echo "No voucher ID provided.";
    exit();
}

$voucher_id = intval($_GET['id']);
$seller_id  = $_SESSION['user']['id'];

// Fetch voucher (only seller's own voucher)
$stmt = $conn->prepare(
    "SELECT * FROM vouchers WHERE id = ? AND seller_id = ?"
);
$stmt->bind_param("ii", $voucher_id, $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$voucher = $result->fetch_assoc();
$stmt->close();

if (!$voucher) {
    echo "Voucher not found!";
    exit();
}

// Update voucher
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $code        = $_POST['code'];
    $discount    = $_POST['discount'];
    $expiry_date = $_POST['expiry_date'];

    $stmt = $conn->prepare(
        "UPDATE vouchers 
         SET code = ?, discount = ?, expiry_date = ?
         WHERE id = ? AND seller_id = ?"
    );

    $stmt->bind_param(
        "sdsii",
        $code,
        $discount,
        $expiry_date,
        $voucher_id,
        $seller_id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Voucher updated successfully!'); window.location='../View/sellerdashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating voucher!');</script>";
    }

    $stmt->close();
}

$db->closeConnection($conn);
?>
