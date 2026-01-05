<?php
session_start();
include "../Model/DatabaseConnection.php";

$email = $_POST["email"];
$password = $_POST["password"];

if (!$email || !$password) {
    $_SESSION["error"] = "Email & Password required";
    header("Location: ../View/login.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();
$result = $db->signin($conn, "users", $email);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user["password"])) {
        $_SESSION["isLoggedIn"] = true;
        $_SESSION["user"] = $user;

        
        if ($user['role'] === 'admin') {
            header("Location: ../View/Admindashboard.php");
        } else {
            header("Location: ../View/customer_dashboard.php");
        }

    } else {
        $_SESSION["error"] = "Invalid credentials";
        header("Location: ../View/login.php");
    }
} else {
    $_SESSION["error"] = "Invalid credentials";
    header("Location: ../View/login.php");
}

$db->closeConnection($conn);
