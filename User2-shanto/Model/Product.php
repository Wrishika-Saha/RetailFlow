<?php
/**
 * Product Model - Handle product management
 */

class Product {
    private $conn;
    private $table = 'products';

    public function __construct($database) {
        $this->conn = $database->connect();
    }

    // ✅ Add product (seller_id will be saved here)
    public function addProduct($seller_id, $title, $category, $price, $stock, $description, $image) {
        $stmt = $this->conn->prepare(
            "INSERT INTO " . $this->table . "
            (seller_id, title, category, price, stock, description, image, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"
        );

        $stmt->bind_param("issdiss", $seller_id, $title, $category, $price, $stock, $description, $image);

        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true, 'message' => 'Product added', 'product_id' => $this->conn->insert_id];
        }

        $error = $stmt->error;
        $stmt->close();
        return ['success' => false, 'message' => 'Failed to add product: ' . $error];
    }

    // Get seller's products
    public function getSellerProducts($seller_id) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM " . $this->table . " WHERE seller_id = ? ORDER BY created_at DESC"
        );
        $stmt->bind_param("i", $seller_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $products;
    }
}
?>
