<?php
session_start();

require_once '../Model/Database.php';
require_once '../Model/User.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../View/signup.php");
    exit;
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$role = $_POST['role'] ?? 'customer';

// Validate
if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
    header("Location: ../View/signup.php?error=All fields are required");
    exit;
}

if ($password !== $confirm_password) {
    header("Location: ../View/signup.php?error=Passwords do not match");
    exit;
}

if (strlen($password) < 6) {
    header("Location: ../View/signup.php?error=Password must be at least 6 characters");
    exit;
}

$db = new Database();
$user_model = new User($db);
$result = $user_model->register($name, $email, $password, $role);

if ($result['success']) {
    header("Location: ../index.php?page=login&success=" . urlencode("Account created successfully. Please login."));
} else {
    header("Location: ../View/signup.php?error=" . urlencode($result['message']));
}
?>
