<?php
session_start();
include '../Model/DatabaseConnection.php';

$db = new DatabaseConnection();
$conn = $db->openConnection();


if (isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    
    if (empty($product_id)) {
        echo "Product ID is missing.";
        exit();
    }

   
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if ($product) {
       
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        
        $_SESSION['cart'][] = [
            'product_id' => $product['id'],
            'title' => $product['title'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'total_price' => $product['price'] * $quantity
        ];

        echo "<script>
                alert('Product added to cart');
                window.location='../View/view_details.php?id={$product['id']}';
              </script>";
    } else {
        echo "Product not found.";
    }
} else {
    echo "Product ID or Quantity not provided.";
}

$db->closeConnection($conn);
?>
