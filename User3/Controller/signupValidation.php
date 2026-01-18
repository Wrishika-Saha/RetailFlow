<?php
session_start();
include "../Model/DatabaseConnection.php";

$name = $_POST["name"] ?? "";
$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";
$confirm = $_POST["confirm_password"] ?? "";

if (!$name || !$email || !$password || !$confirm) {
    $_SESSION["error"] = "All fields are required";
    header("Location: ../View/signup.php");
    exit;
}

if ($password !== $confirm) {
    $_SESSION["error"] = "Password and Confirm Password do not match";
    header("Location: ../View/signup.php");
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$path = "";
if (!empty($_FILES["profile"]["name"])) {
    $path = "../uploads/" . basename($_FILES["profile"]["name"]);
    move_uploaded_file($_FILES["profile"]["tmp_name"], $path);
}

$db = new DatabaseConnection();
$conn = $db->openConnection();

$result = $db->signup($conn, "users", $name, $email, $hashedPassword, $path);

if ($result) {
    header("Location: ../View/login.php");
} else {
    $_SESSION["error"] = "Signup failed";
    header("Location: ../View/signup.php");
}

$db->closeConnection($conn);
