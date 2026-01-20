<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'seller') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?page=seller-dashboard");
    exit;
}

$product_id = $_POST['product_id'] ?? 0;

if (!$product_id) {
    header("Location: ../index.php?page=seller-dashboard&error=Invalid product");
    exit;
}

require_once '../Model/Database.php';
require_once '../Model/Product.php';

$db = new Database();
$product_model = new Product($db);

// Verify ownership
$product = $product_model->getProductById($product_id);
if (!$product || $product['seller_id'] != $_SESSION['user']['id']) {
    header("Location: ../index.php?page=seller-dashboard&error=Unauthorized");
    exit;
}

$result = $product_model->deleteProduct($product_id, $_SESSION['user']['id']);

if ($result['success']) {
    // Delete image file
    if (file_exists('../Uploads/' . $product['image'])) {
        unlink('../Uploads/' . $product['image']);
    }
    header("Location: ../index.php?page=seller-dashboard&message=Product deleted successfully");
} else {
    header("Location: ../index.php?page=seller-dashboard&error=" . urlencode($result['message']));
}
?>
