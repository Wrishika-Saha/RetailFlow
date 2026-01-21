<?php
include('../Model/view_details.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - RetailFlow</title>
    <link rel="stylesheet" href="view_details.css"> <!-- Link to the external CSS -->
</head>
<body>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="../View/customer_dashboard.php" class="back-btn">Back</a>


    <section id="product-detail">
        <div class="container">
            <!-- Product Card -->
            <div class="product-card">
                <!-- Product Image Displayed -->
                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo $product['title']; ?>" class="product-image">

                <!-- Product Title -->
                <h3><?php echo $product['title']; ?></h3>

                <!-- Product Price -->
                <p class="price">BDT <?php echo $product['price']; ?></p>

                <!-- Product Description -->
                <p class="description"><?php echo $product['description']; ?></p>

                <!-- Stock Availability -->
                <p class="stock">Stock: <?php echo $product['stock']; ?> available</p>

                <!-- Add to Cart Button -->
               
                <form action="../Model/add_to_cart.php" method="POST">


    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">  <!-- Hidden field for product ID -->
    <input type="number" name="quantity" min="1" value="1" required>
    <button type="submit" class="add-to-cart-btn">Add to Cart</button>
</form>

            </div>
        </div>
    </section>

</body>
</html>
