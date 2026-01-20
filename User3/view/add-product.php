<?php
$page_title = "Add Product";
include 'header.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'seller') {
    header("Location: ../index.php");
    exit;
}
?>

<div style="max-width: 600px; margin: 2rem auto;">
    <div class="card">
        <h1>Add New Product</h1>

        
        <form method="POST" action="../Controller/add-product-process.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Product Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="Grains">Grains</option>
                    <option value="Oils">Oils</option>
                    <option value="Sweeteners">Sweeteners</option>
                    <option value="Dairy">Dairy</option>
                    <option value="Snacks">Snacks</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price (৳)</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock Quantity</label>
                <input type="number" id="stock" name="stock" min="0" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
                <small style="color: #999;">Supported formats: JPG, PNG, GIF (Max 5MB)</small>
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 1rem;">Add Product</button>
            <a href="seller-dashboard.php" class="btn secondary" style="width: 100%; padding: 1rem; text-align: center; text-decoration: none; margin-top: 1rem;">Cancel</a>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
