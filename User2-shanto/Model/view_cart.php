<?php
session_start();

// Include database connection (for consistency, even if not used here)
include '../Model/DatabaseConnection.php';
$db = new DatabaseConnection();
$conn = $db->openConnection();
/*
// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../View/login.php');
    exit();
}*/

// Check if the cart exists and is not empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty.'); window.location='../View/customer_dashboard.php';</script>";
    exit();
}

// Handle item deletion from cart
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // Sanitize input

    // Remove the item with matching product_id
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['product_id'] == $delete_id) {
            unset($_SESSION['cart'][$index]);
            break;
        }
    }

    // Re-index array
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    // Redirect back to cart page
    header("Location: ../View/view_cart.php");
    exit();
}

$db->closeConnection($conn);
?>
