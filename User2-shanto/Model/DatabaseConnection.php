<?php
class DatabaseConnection {

    function openConnection() {
        $conn = new mysqli("localhost", "root", "", "retailflow");
        if ($conn->connect_error) {
            die("Connection failed");
        }
        return $conn;
    }

    function signup($conn, $table, $name, $email, $password, $picture, $role='customer') {
        $sql = "INSERT INTO $table (name, email, password, profile_picture, role)
                VALUES ('$name', '$email', '$password', '$picture', '$role')";
        return $conn->query($sql);
    }

    function signin($conn, $table, $email) {
        $sql = "SELECT * FROM $table WHERE email='$email'";
        return $conn->query($sql);
    }

    function checkEmail($conn, $table, $email) {
        $sql = "SELECT id FROM $table WHERE email='$email'";
        return $conn->query($sql);
    }

    function closeConnection($conn) {
        $conn->close();
    }
}
