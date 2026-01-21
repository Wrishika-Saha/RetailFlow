<?php

session_start();
include('../Model/DatabaseConnection.php');


if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    die("Access denied. Admin only.");
}


$db = new DatabaseConnection();
$conn = $db->openConnection();

if (!isset($_GET['id'])) {
    die("User ID missing.");
}

$id = (int)$_GET['id'];
if ($id <= 0) {
    die("Invalid User ID.");
}


$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$editUser = $res->fetch_assoc();
$stmt->close();

if (!$editUser) {
    die("User not found.");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role  = trim($_POST['role'] ?? 'customer');

    $newPassword = trim($_POST['password'] ?? ''); 
    $imageName = $editUser['profile_picture'] ?? null; 

    
    if ($name === '' || $email === '') {
        $error = "Name and Email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!in_array($role, ['admin', 'seller', 'customer'], true)) {
        $error = "Invalid role selected.";
    } else {

        
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {

            $tmp = $_FILES['profile_picture']['tmp_name'];
            $original = $_FILES['profile_picture']['name'];

           
            $safeName = preg_replace("/[^a-zA-Z0-9._-]/", "", $original);
            $imageName = time() . "_" . $safeName;

            $uploadDir = "../uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (!move_uploaded_file($tmp, $uploadDir . $imageName)) {
                $error = "Failed to upload profile picture.";
            } else {
                
                $old = $editUser['profile_picture'] ?? '';
                if ($old && $old !== 'default.png' && file_exists($uploadDir . $old)) {
                    @unlink($uploadDir . $old);
                }
            }
        }

        if ($error === '') {

           
            if ($newPassword !== '') {
               
                $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

                $update = $conn->prepare(
                    "UPDATE users 
                     SET name = ?, email = ?, role = ?, password = ?, profile_picture = ? 
                     WHERE id = ?"
                );
                $update->bind_param("sssssi", $name, $email, $role, $hashed, $imageName, $id);

            } else {
               
                $update = $conn->prepare(
                    "UPDATE users 
                     SET name = ?, email = ?, role = ?, profile_picture = ? 
                     WHERE id = ?"
                );
                $update->bind_param("ssssi", $name, $email, $role, $imageName, $id);
            }

            if ($update->execute()) {
                $update->close();
                header("Location: manage_users.php?updated=1");
                exit();
            } else {
                $error = "Update failed: " . $update->error;
                $update->close();
            }
        }
    }
}

$db->closeConnection($conn);
?>
