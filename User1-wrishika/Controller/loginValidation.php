<?php
session_start();
include "../Model/DatabaseConnection.php";

$email = $_POST["email"] ?? null;
$password = $_POST["password"] ?? null;


if (!$email || !$password) {
    $_SESSION["error"] = "Email & Password required";
    header("Location: ../View/login.php");
    exit;
}


$db = new DatabaseConnection();
$conn = $db->openConnection();


$result = $db->signin($conn, "users", $email);

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();

    
    if (password_verify($password, $user["password"])) {
        $_SESSION["isLoggedIn"] = true;
        $_SESSION["user"] = $user;

        $role = strtolower(trim($user['role']));

        
        if ($role === 'admin') {
            $redirect = "../View/Admindashboard.php";
        } elseif ($role === 'seller') {
            $redirect = "../View/sellerdashboard.php";
        } else {
            $redirect = "../View/customer_dashboard.php";
        }

        $db->closeConnection($conn); 
        header("Location: $redirect");
        exit;
    } else {
        $_SESSION["error"] = "Invalid credentials";
    }
} else {
    $_SESSION["error"] = "Invalid credentials";
}


$db->closeConnection($conn);
header("Location: ../View/login.php");
exit;
?>
