<?php


class User {
    private $conn;
    private $table = 'users';

    public function __construct($database) {
        $this->conn = $database->connect();
    }

    public function register($name, $email, $password, $role = 'customer') {
       
        $stmt = $this->conn->prepare("SELECT id FROM " . $this->table . " WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'Email already exists'];
        }
        $stmt->close();

       
        $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true, 'message' => 'Registration successful'];
        } else {
            $stmt->close();
            return ['success' => false, 'message' => 'Registration failed'];
        }
    }

    
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, name, email, password, role FROM " . $this->table . " WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $stmt->close();
                return ['success' => true, 'user' => ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email'], 'role' => $user['role']]];
            }
        }
        
        $stmt->close();
        return ['success' => false, 'message' => 'Invalid email or password'];
    }

    
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT id, name, email, role FROM " . $this->table . " WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $stmt->close();
            return $user;
        }
        
        $stmt->close();
        return null;
    }

   
    public function updateProfile($id, $name, $email) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true, 'message' => 'Profile updated'];
        } else {
            $stmt->close();
            return ['success' => false, 'message' => 'Update failed'];
        }
    }

    
    public function getAllUsers() {
        $query = "SELECT id, name, email, role FROM " . $this->table;
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
