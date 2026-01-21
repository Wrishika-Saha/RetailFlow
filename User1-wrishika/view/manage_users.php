<?php
session_start();
include('../Model/DatabaseConnection.php');


$db = new DatabaseConnection();
$conn = $db->openConnection();

$result = $conn->query("SELECT * FROM users");

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$db->closeConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
    
    <link rel="stylesheet" href="manage_users.css">
</head>
<body>

    <a href="../Controller/logout.php" class="logout-btn">Logout</a>

    
    <aside id="sidebar">
        <h3>Admin Dashboard</h3>
        <nav class="sidebar-nav">
            <ul>
               <li><a href="Admindashboard.php">Dashboard</a></li>
                <li><a href="manageproduct.php">Manage Products</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="orders_list.php">Manage Orders</a></li>
                <li><a href="managepayment.php">Manage Payments</a></li>
                <li><a href="settings.php">Settings</a></li>
            </ul>
        </nav>
    </aside>

   
    <section id="manage-users">
        <div class="container">
            <h1>Manage Users</h1>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($users)) {
                        foreach ($users as $user) {
                            echo "<tr>
                                <td>{$user['id']}</td>
                                <td>{$user['name']}</td>
                                <td>{$user['email']}</td>
                                <td>{$user['role']}</td>
                                <td>
                                    <a class='btn-edit' href='edituser.php?id={$user['id']}'>Edit</a> |
                                    <a class='btn-delete' href='../Model/deleteuser.php?delete_id={$user['id']}'>Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No users available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

</body>
</html>
