<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'seller') {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . '/../Model/Database.php';
require_once __DIR__ . '/../Model/Product.php';

$db = new Database();
$product = new Product($db);

// ✅ seller_id must come from session
$seller_id = (int)($_SESSION['user']['id'] ?? 0);

if ($seller_id <= 0) {
    die("Seller session ID not found. Please login again.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../seller/add-product.php");
    exit;
}

// Form data
$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$stock = (int)($_POST['stock'] ?? 0);
$description = trim($_POST['description'] ?? '');

// Basic validation
if ($title === '' || $category === '' || $price <= 0 || $stock < 0 || $description === '') {
    $_SESSION['error'] = "Please fill all fields correctly.";
    header("Location: ../seller/add-product.php");
    exit;
}

// Image validation
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = "Image upload failed.";
    header("Location: ../seller/add-product.php");
    exit;
}

$allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
$maxSize = 5 * 1024 * 1024; // 5MB

$fileTmp = $_FILES['image']['tmp_name'];
$fileName = $_FILES['image']['name'];
$fileSize = $_FILES['image']['size'];

$ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if (!in_array($ext, $allowedExt)) {
    $_SESSION['error'] = "Invalid image format. Use JPG, PNG, or GIF.";
    header("Location: ../seller/add-product.php");
    exit;
}

if ($fileSize > $maxSize) {
    $_SESSION['error'] = "Image too large. Max 5MB.";
    header("Location: ../seller/add-product.php");
    exit;
}

// Upload location
$uploadDir = __DIR__ . '/../uploads/products/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Save filename only (like your screenshot: rice.jpg / oil.jpg style)
$newFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "", $fileName);
$destination = $uploadDir . $newFileName;

if (!move_uploaded_file($fileTmp, $destination)) {
    $_SESSION['error'] = "Failed to save uploaded image.";
    header("Location: ../seller/add-product.php");
    exit;
}

// ✅ Insert with seller_id
$result = $product->addProduct($seller_id, $title, $category, $price, $stock, $description, $newFileName);

if (!empty($result['success'])) {
    $_SESSION['success'] = "Product added successfully!";
    header("Location: ../seller/seller-dashboard.php");
    exit;
}

$_SESSION['error'] = $result['message'] ?? "Failed to add product.";
header("Location: ../seller/add-product.php");
exit;
