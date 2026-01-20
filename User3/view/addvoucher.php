<?php
session_start();
include '../Model/DatabaseConnection.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'seller') {
    header("Location: ../index.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $seller_id = (int)($_SESSION['user']['id'] ?? 0);
    $code = trim($_POST['code'] ?? '');
    $discount = (int)($_POST['discount'] ?? 0);
    $expiry_date = $_POST['expiry_date'] ?? '';

    
    if ($seller_id <= 0) {
        die("Seller ID missing. Please login again.");
    }

    if ($code === '' || $discount <= 0 || $discount > 100 || $expiry_date === '') {
        echo "<script>alert('Please enter valid voucher data (discount 1-100).');</script>";
    } else {
        
        $check = $conn->prepare("SELECT id FROM vouchers WHERE seller_id = ? AND code = ?");
        $check->bind_param("is", $seller_id, $code);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            echo "<script>alert('This voucher code already exists for you. Use a different code.');</script>";
        } else {
           
            $stmt = $conn->prepare(
                "INSERT INTO vouchers (seller_id, code, discount, expiry_date)
                 VALUES (?, ?, ?, ?)"
            );

            
            $stmt->bind_param("isis", $seller_id, $code, $discount, $expiry_date);

            if ($stmt->execute()) {
                echo "<script>alert('Voucher added successfully!'); window.location='sellerdashboard.php';</script>";
                exit;
            } else {
                echo "<script>alert('Database Error: " . addslashes($stmt->error) . "');</script>";
            }

            $stmt->close();
        }

        $check->close();
    }
}

$db->closeConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Voucher</title>
    <link rel="stylesheet" href="addvoucher.css">
</head>
<body>
    <div class="card">
        <h1>Add New Voucher</h1>

        
        <form method="POST" action="">
            <input type="text" name="code" placeholder="Voucher Code" required>
            <input type="number" name="discount" placeholder="Discount (%)" min="1" max="100" required>
            <input type="date" name="expiry_date" required>
            <button type="submit">Save Voucher</button>
        </form>

        <div class="back-link">
            <a href="sellerdashboard.php">&lt;&lt; Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
