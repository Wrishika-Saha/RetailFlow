<?php
session_start();
include '../Model/DatabaseConnection.php';


if (
    !isset($_SESSION['user']) ||
    !in_array($_SESSION['user']['role'], ['seller', 'admin'])
) {
    header("Location: ../View/login.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();


if (!isset($_GET['id'])) {
    die("Product ID missing.");
}

$id = (int)$_GET['id'];
if ($id <= 0) {
    die("Invalid Product ID.");
}


$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$product = $res->fetch_assoc();
$stmt->close();

if (!$product) {
    die("Product not found.");
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    if ($title === '' || $category === '' || $price <= 0 || $stock < 0 || $description === '') {
        die("Invalid input. Please fill all fields correctly.");
    }


   
    $redirectTo =
        $_POST['redirect_to']
        ?? $_SERVER['HTTP_REFERER']
        ?? '../View/admin_dashboard.php';


   
    $imageName = $product['image']; 

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $tmp = $_FILES['image']['tmp_name'];
        $original = $_FILES['image']['name'];

        $safeName = preg_replace("/[^a-zA-Z0-9._-]/", "", $original);
        $imageName = time() . "_" . $safeName;

        $uploadDir = "../uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!move_uploaded_file($tmp, $uploadDir . $imageName)) {
            die("Failed to upload image.");
        }

        
        if (!empty($product['image']) && file_exists($uploadDir . $product['image'])) {
            @unlink($uploadDir . $product['image']);
        }
    }

   
    $update = $conn->prepare(
        "UPDATE products
         SET title = ?, category = ?, price = ?, stock = ?, description = ?, image = ?
         WHERE id = ?"
    );

    
    $update->bind_param(
        "ssdissi",
        $title,
        $category,
        $price,
        $stock,
        $description,
        $imageName,
        $id
    );

    if ($update->execute()) {
        header("Location: " . $redirectTo);
        exit;
    } else {
        die("Update failed: " . $update->error);
    }
}
?>

