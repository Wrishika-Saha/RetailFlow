<?php


class Order {
    private $conn;
    private $table = 'orders';
    private $table_items = 'order_items';

    public function __construct($database) {
        $this->conn = $database->connect();
    }

    
    public function createOrder($user_id, $total_amount, $payment_method = null, $status = 'pending') {

      
        $allowed = ['pending', 'completed', 'shipped'];
        if (!in_array($status, $allowed)) {
            $status = 'pending';
        }

        
        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (user_id, total_amount, status, payment_method, order_date)
             VALUES (?, ?, ?, ?, NOW())"
        );

        $stmt->bind_param("idss", $user_id, $total_amount, $status, $payment_method);

        if ($stmt->execute()) {
            $order_id = $this->conn->insert_id;
            $stmt->close();
            return ['success' => true, 'order_id' => $order_id];
        } else {
            $error = $stmt->error;
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to create order: ' . $error];
        }
    }

    
    public function addOrderItems($order_id, $cart_items) {

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table_items} (order_id, product_id, quantity, price)
             VALUES (?, ?, ?, ?)"
        );

        foreach ($cart_items as $item) {
            $product_id = (int)$item['product_id'];
            $quantity = (int)$item['quantity'];
            $price = (float)$item['price'];

            $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            $stmt->execute();
        }

        $stmt->close();
        return ['success' => true];
    }

   
    public function getUserOrders($user_id) {
        $query = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY order_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $orders;
    }

    
    public function getOrderDetails($order_id) {
        $query = "SELECT oi.*, p.title, p.image
                  FROM {$this->table_items} oi
                  JOIN products p ON oi.product_id = p.id
                  WHERE oi.order_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $items;
    }

 
    public function getOrderById($id) {
        $query = "SELECT o.*, u.name, u.email
                  FROM {$this->table} o
                  JOIN users u ON o.user_id = u.id
                  WHERE o.id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $order = $result->fetch_assoc();
            $stmt->close();
            return $order;
        }

        $stmt->close();
        return null;
    }

   
    public function updateOrderStatus($order_id, $status) {

        $allowed = ['pending', 'completed', 'shipped'];
        if (!in_array($status, $allowed)) {
            return ['success' => false, 'message' => 'Invalid status'];
        }

        $stmt = $this->conn->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);

        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true, 'message' => 'Order status updated'];
        } else {
            $error = $stmt->error;
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to update: ' . $error];
        }
    }

    
    public function getAllOrders() {
        $query = "SELECT o.*, u.name, u.email
                  FROM {$this->table} o
                  JOIN users u ON o.user_id = u.id
                  ORDER BY o.order_date DESC";

        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
?>
