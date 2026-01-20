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

$title = $_POST['title'] ?? '';

$category = $_POST['category'] ?? '';
$price = $_POST['price'] ?? 0;
$stock = $_POST['stock'] ?? 0;
$description = $_POST['description'] ?? '';

if (!$product_id || empty($title) || empty($category) || $price <= 0 || $stock < 0 || empty($description)) {
    header("Location: ../index.php?page=edit-product&id=$product_id&error=Please fill all fields correctly");
    exit;
}

require_once '../Model/Database.php';

require_once '../Model/Product.php';

$db = new Database();
$product_model = new Product($db);


$product = $product_model->getProductById($product_id);
if (!$product || $product['seller_id'] != $_SESSION['user']['id']) {
    header("Location: ../index.php?page=seller-dashboard&error=Unauthorized");
    exit;
}

$image_name = $product['image'];


if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $image_file = $_FILES['image'];
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_ext = strtolower(pathinfo($image_file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_extensions)) {
        header("Location: ../index.php?page=edit-product&id=$product_id&error=Only JPG, PNG, and GIF files are allowed");
        exit;
    }

    if ($image_file['size'] > 5 * 1024 * 1024) {
        header("Location: ../index.php?page=edit-product&id=$product_id&error=Image size must be less than 5MB");
        exit;
    }

    
    $image_name = time() . '_' . basename($image_file['name']);
    $upload_path = '../Uploads/' . $image_name;

    if (!move_uploaded_file($image_file['tmp_name'], $upload_path)) {
        header("Location: ../index.php?page=edit-product&id=$product_id&error=Failed to upload image");
        exit;
    }


    if (file_exists('../Uploads/' . $product['image'])) {
        unlink('../Uploads/' . $product['image']);
    }
}

$result = $product_model->updateProduct($product_id, $_SESSION['user']['id'], $title, $category, $price, $stock, $description, $image_name);

if ($result['success']) {
    header("Location: ../index.php?page=seller-dashboard&message=Product updated successfully");
} else {
    header("Location: ../index.php?page=edit-product&id=$product_id&error=" . urlencode($result['message']));
}
?>

