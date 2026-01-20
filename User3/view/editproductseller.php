<?php
include('../Model/editproduct.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="editproduct.css">
    <link rel="stylesheet" href="addproduct.css">

</head>
<body>

    <a href="sellerdashboard.php"> << Back to Dashboard</a>
    <section id="edit-product">
                <div class="container">
                    <h1>Edit Product</h1>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="text" name="title" placeholder="Product Title" value="<?= htmlspecialchars($product['title']); ?>" required>
                        <input type="text" name="category" placeholder="Product Category" value="<?= htmlspecialchars($product['category']); ?>" required>
                        <input type="number" step="0.01" name="price" placeholder="Price (BDT)" value="<?= htmlspecialchars($product['price']); ?>" required>
                        <input type="number" name="stock" placeholder="Stock Quantity" value="<?= htmlspecialchars($product['stock']); ?>" required>
                        <textarea name="description" placeholder="Description" required><?= htmlspecialchars($product['description']); ?></textarea>

                        
                        <div>
                            <p>Current Image:</p>
                            <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" width="100" height="100">
                        </div>

                        
                        <input type="file" name="image" accept="image/*">
                        <input type="hidden" name="redirect_to"
       value="<?php echo htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'admin_dashboard.php'); ?>">

                        <button type="submit">Update Product</button>
                    </form>
                </div>
            </section>
        </div>
        
    </section>

    

</body>
</html>
