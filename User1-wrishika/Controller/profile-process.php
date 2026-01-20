<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php?page=login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?page=profile");
    exit;
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';

if (empty($name) || empty($email)) {
    header("Location: ../index.php?page=profile&error=All fields are required");
    exit;
}

require_once '../Model/Database.php';
require_once '../Model/User.php';

$db = new Database();
$user_model = new User($db);
$result = $user_model->updateProfile($_SESSION['user']['id'], $name, $email);

if ($result['success']) {
    // Update session
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['email'] = $email;
    header("Location: ../index.php?page=profile&message=" . urlencode($result['message']));
} else {
    header("Location: ../index.php?page=profile&error=" . urlencode($result['message']));
}
?>
