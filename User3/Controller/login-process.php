<?php
session_start();

require_once '../Model/Database.php';
require_once '../Model/User.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../View/login.php");
    
    exit;
}

$email = $_POST['email'] ?? '';

$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    
    header("Location: ../View/login.php?error=Email and password are required");
    exit;
}

$db = new Database();

$user_model = new User($db);

$result = $user_model->login($email, $password);

if ($result['success']) {
    
    $_SESSION['user'] = $result['user'];
   
    $page = ($result['user']['role'] === 'admin') ? 'admin-dashboard' : 'customer-dashboard';
    
    header("Location: ../index.php?page=$page&message=Login successful");
} else {
    
    header("Location: ../View/login.php?error=" . urlencode($result['message']));
}
?>

