<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php?page=login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

$product_id = $_POST['product_id'] ?? 0;
$quantity = $_POST['quantity'] ?? 1;

if (!$product_id || !$quantity) {
    header("Location: ../index.php?error=Invalid product");
    exit;
}

require_once '../Model/Database.php';
require_once '../Model/Cart.php';

$db = new Database();
$cart = new Cart($db);
$result = $cart->addToCart($_SESSION['user']['id'], $product_id, $quantity);

if ($result['success']) {
    if (isset($_POST['redirect'])) {
        header("Location: ../index.php?page=" . $_POST['redirect'] . "&message=Item added to cart");
    } else {
        header("Location: ../index.php?page=cart&message=Item added to cart");
    }
} else {
    header("Location: ../index.php?error=" . urlencode($result['message']));
}
?>
