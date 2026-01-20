<?php
include('../Model/product_detail.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Market Mingle</title>
    <link rel="stylesheet" href="product_detail.css"> 
</head>
<body>

    <?php include('header.php'); ?> 

    <a href="../View/customer_dashboard.php" class="back-btn">Back</a>

    <section id="product-detail">
        <div class="container">
            <div class="product-card">
                
                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['title']); ?>" 
                     class="product-image">

                
                <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                <p class="price">BDT <?php echo htmlspecialchars($product['price']); ?></p>
                <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                <p class="stock">Stock: <?php echo htmlspecialchars($product['stock']); ?> available</p>

                
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="number" name="quantity" min="1" value="1" required>
                    <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                </form>
            </div>
        </div>
    </section>

    <?php include('footer.php'); ?> 

</body>
</html>
