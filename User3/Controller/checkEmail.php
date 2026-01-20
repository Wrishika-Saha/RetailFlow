<?php
include "../Model/DatabaseConnection.php";

$email = $_POST["Email"] ?? "";

if ($email == "") {
    echo "Email required";
    exit;
}

$db = new DatabaseConnection();

$conn = $db->openConnection();

$result = $db->checkEmail($conn, "users", $email);

if ($result->num_rows > 0) {
    echo "Email already used";
} else {
    echo "Email available";
}
$db->closeConnection($conn);
