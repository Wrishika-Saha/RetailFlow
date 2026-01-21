<?php


class Cart {
    private $conn;
    private $table = 'cart';

    public function __construct($database) {
        $this->conn = $database->connect();
    }

   
    public function addToCart($user_id, $product_id, $quantity) {
       
        $stmt = $this->conn->prepare("SELECT id, quantity FROM " . $this->table . " WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $cart_item = $result->fetch_assoc();
            $new_quantity = $cart_item['quantity'] + $quantity;
            $stmt->close();
            return $this->updateCartItem($cart_item['id'], $new_quantity);
        }

        $stmt->close();

        
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);

        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true, 'message' => 'Item added to cart'];
        } else {
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to add item'];
        }
    }

    
    public function getCartItems($user_id) {
        $query = "SELECT c.id, c.quantity, p.id as product_id, p.title, p.price, p.image, p.stock FROM " . $this->table . " c 
                  JOIN products p ON c.product_id = p.id 
                  WHERE c.user_id = ?
                  ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $items;
    }

    
    public function updateCartItem($cart_id, $quantity) {
        if ($quantity <= 0) {
            return $this->removeFromCart($cart_id);
        }

        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $cart_id);

        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true, 'message' => 'Cart updated'];
        } else {
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to update cart'];
        }
    }

    
    public function removeFromCart($cart_id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = ?");
        $stmt->bind_param("i", $cart_id);

        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true, 'message' => 'Item removed'];
        } else {
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to remove item'];
        }
    }

    
    public function clearCart($user_id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true, 'message' => 'Cart cleared'];
        } else {
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to clear cart'];
        }
    }

    
    public function getCartTotal($user_id) {
        $stmt = $this->conn->prepare("SELECT SUM(p.price * c.quantity) as total FROM " . $this->table . " c 
                                       JOIN products p ON c.product_id = p.id 
                                       WHERE c.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'] ?? 0;
    }
}
?>
