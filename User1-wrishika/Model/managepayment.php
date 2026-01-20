<?php
session_start();

include '../Model/DatabaseConnection.php';

// Check admin login
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../View/login.php");
    exit();
}

$db = new DatabaseConnection();
$conn = $db->openConnection();


// Handle payment deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    $stmt = $conn->prepare("DELETE FROM payments WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Payment deleted successfully!');
                window.location='../View/manage_payments.php';
              </script>";
        exit();
    } else {
        echo "Error deleting payment!";
    }

    $stmt->close();
}


// Fetch all payments
$payments = [];
$result = $conn->query("SELECT * FROM payments ORDER BY id DESC");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
}

$db->closeConnection($conn);
?>
