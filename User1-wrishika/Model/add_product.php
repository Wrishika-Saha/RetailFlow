<?php
session_start();

include '../Model/DatabaseConnection.php'; 

$db = new DatabaseConnection();
$conn = $db->openConnection();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $seller_id = ($_SESSION['user']['id']);

    $imageName = '';

    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = time() . '_' . $_FILES['image']['name']; 
        $destination = '../uploads/' . $imageName; 

        
        if (!move_uploaded_file($imageTmpName, $destination)) {
            echo "<script>alert('Failed to upload image');</script>";
            exit();
        }
    } else {
        echo "<script>alert('Please upload an image');</script>";
        exit();
    }



    $stmt = $conn->prepare("INSERT INTO products (seller_id, title, category, price, stock, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdiss", $seller_id, $title, $category, $price, $stock, $description, $imageName);

    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully'); window.location='addproduct.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$db->closeConnection($conn);
?>
