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

$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    header("Location: ../index.php?page=profile&error=All fields are required");
    exit;
}

if ($new_password !== $confirm_password) {
    header("Location: ../index.php?page=profile&error=New passwords do not match");
    exit;
}

if (strlen($new_password) < 6) {
    header("Location: ../index.php?page=profile&error=Password must be at least 6 characters");
    exit;
}

require_once '../Model/Database.php';

$db = new Database();
$conn = $db->connect();

// Get current password hash
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user']['id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($current_password, $user['password'])) {
    header("Location: ../index.php?page=profile&error=Current password is incorrect");
    exit;
}

// Update password
$hashed_password = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->bind_param("si", $hashed_password, $_SESSION['user']['id']);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: ../index.php?page=profile&message=Password changed successfully");
} else {
    $stmt->close();
    header("Location: ../index.php?page=profile&error=Failed to change password");
}
?>
