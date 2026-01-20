<?php
session_start();

include '../Model/DatabaseConnection.php';

$db = new DatabaseConnection();
$conn = $db->openConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Prepare query
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check user & password
    if ($user && password_verify($password, $user['password'])) {

        // Store user info in session
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'profile_picture' => $user['profile_picture'] ?? 'default.png'
        ];

        // Remember Me
        if (isset($_POST['remember_me'])) {
            setcookie('user_email', $email, time() + (30 * 24 * 60 * 60), "/");
            setcookie('user_password', $password, time() + (30 * 24 * 60 * 60), "/");
        } else {
            setcookie('user_email', '', time() - 3600, "/");
            setcookie('user_password', '', time() - 3600, "/");
        }

        // Role-based redirect
        if ($user['role'] === 'admin') {
            header("Location: ../View/admin_dashboard.php");
        } elseif ($user['role'] === 'seller') {
            header("Location: ../View/sellerdashboard.php");
        } elseif ($user['role'] === 'customer') {
            header("Location: ../View/customer_dashboard.php");
        } else {
            echo "<script>alert('Role not recognized'); window.location='../View/login.php';</script>";
        }
        exit;

    } else {
        echo "<script>alert('Invalid email or password'); window.location='../View/login.php';</script>";
    }

    $stmt->close();
}

$db->closeConnection($conn);
?>
