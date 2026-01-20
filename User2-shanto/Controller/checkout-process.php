<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

require_once '../Model/Database.php';
require_once '../Model/Cart.php';
require_once '../Model/Order.php';
require_once '../Model/Payment.php';

$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$city = $_POST['city'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';

if (empty($phone) || empty($address) || empty($city) || empty($payment_method)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

$db = new Database();

// Get cart items
$cart = new Cart($db);
$cart_items = $cart->getCartItems($_SESSION['user']['id']);

if (empty($cart_items)) {
    echo json_encode(['success' => false, 'message' => 'Your cart is empty']);
    exit;
}

// Calculate total
$total = $cart->getCartTotal($_SESSION['user']['id']);

// Create order
$order_model = new Order($db);
$order_result = $order_model->createOrder($_SESSION['user']['id'], $total);

if (!$order_result['success']) {
    echo json_encode(['success' => false, 'message' => 'Failed to create order']);
    exit;
}

$order_id = $order_result['order_id'];

// Add order items
$items_to_add = [];
foreach ($cart_items as $item) {
    $items_to_add[] = [
        'product_id' => $item['product_id'],
        'quantity' => $item['quantity'],
        'price' => $item['price']
    ];
}
$order_model->addOrderItems($order_id, $items_to_add);

// Record payment
$payment_model = new Payment($db);
$payment_result = $payment_model->recordPayment($order_id, $_SESSION['user']['id'], $total, $payment_method);

if ($payment_result['success']) {
    // Clear cart
    $cart->clearCart($_SESSION['user']['id']);
    
    // Update order status to confirmed
    $order_model->updateOrderStatus($order_id, 'processing');
    
    echo json_encode(['success' => true, 'order_id' => $order_id, 'message' => 'Order placed successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Payment processing failed']);
}
?>
