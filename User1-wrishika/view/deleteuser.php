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



if (!isset($_GET['delete_id'])) {
    header("Location: manage_users.php?error=missing_id");
    exit();
}

$delete_id = (int)$_GET['delete_id'];
if ($delete_id <= 0) {
    header("Location: manage_users.php?error=invalid_id");
    exit();
}


$myId = (int)($_SESSION['user']['id'] ?? 0);
if ($myId === $delete_id) {
    header("Location: manage_users.php?error=cannot_delete_self");
    exit();
}


$db = new DatabaseConnection();
$conn = $db->openConnection();


$stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $delete_id);
$stmt->execute();
$res = $stmt->get_result();
$userRow = $res->fetch_assoc();
$stmt->close();

if (!$userRow) {
    $db->closeConnection($conn);
    header("Location: manage_users.php?error=user_not_found");
    exit();
}


$del = $conn->prepare("DELETE FROM users WHERE id = ?");
$del->bind_param("i", $delete_id);

if ($del->execute()) {
    $del->close();

    
    $pic = $userRow['profile_picture'] ?? '';
    $uploadDir = "../uploads/";

    if ($pic && $pic !== "default.png") {
        $filePath = $uploadDir . $pic;
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }

    $db->closeConnection($conn);
    header("Location: manage_users.php?deleted=1");
    exit();
} else {
    $error = $del->error;
    $del->close();
    $db->closeConnection($conn);
    header("Location: manage_users.php?error=delete_failed");
    exit();
}
?>
