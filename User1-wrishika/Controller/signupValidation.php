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


$db = new DatabaseConnection();
$conn = $db->openConnection();


$existingEmail = $db->checkEmail($conn, "users", $email);
if ($existingEmail && $existingEmail->num_rows > 0) {
    $_SESSION["error"] = "Email already exists. Please use a different email.";
    header("Location: ../View/signup.php");
    exit;
}


$hashedPassword = password_hash($password, PASSWORD_DEFAULT);


$path = "";
if (!empty($_FILES["profile"]["name"])) {
    $targetDir = "../uploads/";
    $path = $targetDir . basename($_FILES["profile"]["name"]);

    if (!move_uploaded_file($_FILES["profile"]["tmp_name"], $path)) {
        $_SESSION["error"] = "Failed to upload profile picture.";
        header("Location: ../View/signup.php");
        exit;
    }
}


try {
    $result = $db->signup($conn, "users", $name, $email, $hashedPassword, $path);

    if ($result) {
        $_SESSION["success"] = "Signup successful! You can now login.";
        header("Location: ../View/login.php");
        exit;
    } else {
        $_SESSION["error"] = "Signup failed. Please try again.";
        header("Location: ../View/signup.php");
        exit;
    }
} catch (mysqli_sql_exception $e) {
    
    $_SESSION["error"] = "Email already exists or database error.";
    header("Location: ../View/signup.php");
    exit;
}


$db->closeConnection($conn);
?>
