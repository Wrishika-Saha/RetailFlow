<?php
session_start();

if (!($_SESSION["isLoggedIn"] ?? false)) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$seller_id = $user['id'];
$name = $user['name'] ?? 'Seller';

include(__DIR__ . '/../Model/DatabaseConnection.php');
$db = new DatabaseConnection();
$conn = $db->openConnection();

$vouchers = [];
$sql = "SELECT * FROM vouchers WHERE seller_id = '$seller_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vouchers[] = $row;
    }
}

$db->closeConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Vouchers</title>
    <link rel="stylesheet" href="sellerdashboard.css">
</head>
<body>

<h1>My Vouchers</h1>
<p>Seller: <?php echo htmlspecialchars($name); ?></p>

<a class="btn" href="sellerdashboard.php">← Back to Dashboard</a>
<a class="btn" href="addvoucher.php">+ Add Voucher</a>

<table>
    <tr>
        <th>Voucher Code</th>
        <th>Discount (%)</th>
        <th>Expiry Date</th>
        <th>Actions</th>
    </tr>

    <?php if (!empty($vouchers)) { foreach ($vouchers as $v) { ?>
        <tr>
            <td><?= $v['code']; ?></td>
            <td><?= $v['discount']; ?></td>
            <td><?= $v['expiry_date']; ?></td>
            <td>
                <a class="btn" href="editvoucher.php?id=<?= $v['id']; ?>">Edit</a>
                <a class="btn btn-red" href="deletevoucher.php?id=<?= $v['id']; ?>">Delete</a>
            </td>
        </tr>
    <?php } } else { ?>
        <tr>
            <td colspan="4">No vouchers found</td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
