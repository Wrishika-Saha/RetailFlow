<?php
session_start();
require_once '../Model/Database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}

$seller_id = (int)($_SESSION['user']['id'] ?? 0);
if ($seller_id <= 0) {
    die("Seller ID missing. Please login again.");
}

$db = new Database();
$conn = $db->connect();


$voucher_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($voucher_id <= 0) {
    die("Invalid Voucher ID.");
}


$stmt = $conn->prepare("SELECT * FROM vouchers WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $voucher_id, $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$voucher = $result->fetch_assoc();
$stmt->close();

if (!$voucher) {
    die("Voucher not found or you don't have permission to edit it.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $code = trim($_POST['code'] ?? '');
    $discount = (int)($_POST['discount'] ?? 0);
    $expiry_date = $_POST['expiry_date'] ?? '';

    
    if ($code === '' || $discount < 1 || $discount > 100 || $expiry_date === '') {
        $error = "Please enter valid data (discount must be 1-100).";
    } else {

        
        $check = $conn->prepare("SELECT id FROM vouchers WHERE seller_id = ? AND code = ? AND id != ?");
        $check->bind_param("isi", $seller_id, $code, $voucher_id);
        $check->execute();
        $checkRes = $check->get_result();

        if ($checkRes->num_rows > 0) {
            $error = "This voucher code already exists. Use a different code.";
        }

        $check->close();

        if (!isset($error)) {
            $update = $conn->prepare(
                "UPDATE vouchers
                 SET code = ?, discount = ?, expiry_date = ?
                 WHERE id = ? AND seller_id = ?"
            );

            
            $update->bind_param("sisii", $code, $discount, $expiry_date, $voucher_id, $seller_id);

            if ($update->execute()) {
                header("Location: sellerdashboard.php?voucher_updated=1");
                exit;
            } else {
                $error = "Update failed: " . $update->error;
            }

            $update->close();
        }
    }

   
    $voucher['code'] = $code;
    $voucher['discount'] = $discount;
    $voucher['expiry_date'] = $expiry_date;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Voucher</title>
    <link rel="stylesheet" href="addvoucher.css">
    <style>
        .error-box{
            background:#fee2e2;
            border:1px solid #fecaca;
            color:#991b1b;
            padding:10px;
            border-radius:6px;
            margin-bottom:12px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Edit Voucher</h1>

        <?php if (!empty($error)): ?>
            <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="code" placeholder="Voucher Code"
                   value="<?php echo htmlspecialchars($voucher['code']); ?>" required>

            <input type="number" name="discount" placeholder="Discount (%)"
                   min="1" max="100"
                   value="<?php echo (int)$voucher['discount']; ?>" required>

            <input type="date" name="expiry_date"
                   value="<?php echo htmlspecialchars($voucher['expiry_date']); ?>" required>

            <button type="submit">Update Voucher</button>
        </form>

        <div class="back-link">
            <a href="sellerdashboard.php">&lt;&lt; Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
