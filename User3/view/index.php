<?php

include('../Model/index.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Mingle - Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include('header.php'); ?>

    
    <section id="hero">
        <div class="hero-content">
            <h1>Welcome to RetailFlow</h1>
            <p>Your one-stop shop for buying and selling products.</p>
        </div>
    </section>

    
    <section id="featured-products">
        <h2>All Products</h2>
        <div class="product-grid">
            <?php
            if (!empty($products)) {
                foreach ($products as $product) {
                    $imageSrc = '../uploads/' . $product['image'];
                    $title = htmlspecialchars($product['title']);
                    $description = htmlspecialchars($product['description']);
                    $price = htmlspecialchars($product['price']);
                    $id = (int)$product['id'];

                    
                    $detailsLink = isset($_SESSION['user'])
                        ? "product_detail.php?id={$id}"
                        : "login.php";

                    echo "<div class='product-card'>
                            <img src='{$imageSrc}' alt='{$title}' class='product-image'>
                            <h3>{$title}</h3>
                            <p class='price'>BDT {$price}</p>
                            <p class='description'>{$description}</p>
                            <a href='{$detailsLink}' class='btn-view-details'>View Details</a>
                          </div>";
                }
            } else {
                echo "<p>No products available.</p>";
            }
            ?>
        </div>
    </section>

    <?php include('footer.php'); ?>
</body>
</html>
