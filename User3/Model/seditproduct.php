<?php
session_start();

include '../Model/DatabaseConnection.php';

$db = new DatabaseConnection();
$conn = $db->openConnection();


if (!isset($_SESSION['isLoggedIn']) || $_SESSION['user']['role'] !== 'seller') {
    header("Location: ../View/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Product not found.";
    exit();
}

$product_id = $_GET['id'];


$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $product_id, $_SESSION['user']['id']);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = $_POST['title'];
    $category    = $_POST['category'];
    $price       = $_POST['price'];
    $stock       = $_POST['stock'];
    $description = $_POST['description'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = time() . '_' . $_FILES['image']['name'];
        $destination = '../uploads/' . $imageName;

        if (!move_uploaded_file($imageTmpName, $destination)) {
            echo "<script>alert('Image upload failed');</script>";
            exit();
        }

        $stmt = $conn->prepare(
            "UPDATE products SET title=?, category=?, price=?, stock=?, description=?, image=? WHERE id=? AND seller_id=?"
        );
        $stmt->bind_param("ssdiisii", $title, $category, $price, $stock, $description, $imageName, $product_id, $_SESSION['user']['id']);
    } else {
        $stmt = $conn->prepare(
            "UPDATE products SET title=?, category=?, price=?, stock=?, description=? WHERE id=? AND seller_id=?"
        );
        $stmt->bind_param("ssdiisi", $title, $category, $price, $stock, $description, $product_id, $_SESSION['user']['id']);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location='../View/sellerdashboard.php';</script>";
    } else {
        echo "Error updating product: " . $stmt->error;
    }

    $stmt->close();
}

$db->closeConnection($conn);
?>

