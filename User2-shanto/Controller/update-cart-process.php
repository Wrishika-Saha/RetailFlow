<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php?page=login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?page=cart");
    exit;
}

$cart_id = $_POST['cart_id'] ?? 0;
$quantity = $_POST['quantity'] ?? 0;

if (!$cart_id || !$quantity) {
    header("Location: ../index.php?page=cart&error=Invalid request");
    exit;
}

require_once '../Model/Database.php';
require_once '../Model/Cart.php';

$db = new Database();
$cart = new Cart($db);
$result = $cart->updateCartItem($cart_id, $quantity);

if ($result['success']) {
    header("Location: ../index.php?page=cart&message=Cart updated");
} else {
    header("Location: ../index.php?page=cart&error=" . urlencode($result['message']));
}
?>
