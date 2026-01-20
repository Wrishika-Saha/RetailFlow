<?php
session_start();


include '../Model/DatabaseConnection.php';
$db = new DatabaseConnection();
$conn = $db->openConnection();



if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty.'); window.location='../View/customer_dashboard.php';</script>";
    exit();
}


if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); 

    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['product_id'] == $delete_id) {
            unset($_SESSION['cart'][$index]);
            break;
        }
    }

   
    $_SESSION['cart'] = array_values($_SESSION['cart']);

   
    header("Location: ../View/view_cart.php");
    exit();
}

$db->closeConnection($conn);
?>
