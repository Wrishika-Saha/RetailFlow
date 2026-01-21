<?php
include('../Model/view_details.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - RetailFlow</title>
    <link rel="stylesheet" href="view_details.css"> 
</head>
<body>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="../View/customer_dashboard.php" class="back-btn">Back</a>


    <section id="product-detail">
        <div class="container">
           
            <div class="product-card">
                
                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo $product['title']; ?>" class="product-image">

                
                <h3><?php echo $product['title']; ?></h3>

                <p class="price">BDT <?php echo $product['price']; ?></p>

             
                <p class="description"><?php echo $product['description']; ?></p>

              
                <p class="stock">Stock: <?php echo $product['stock']; ?> available</p>

               
                <form action="../Model/add_to_cart.php" method="POST">


    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">  
    <input type="number" name="quantity" min="1" value="1" required>
    <button type="submit" class="add-to-cart-btn">Add to Cart</button>
</form>

            </div>
        </div>
    </section>

</body>
</html>
