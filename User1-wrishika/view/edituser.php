<?php include('../Model/edituser.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>

    <link rel="stylesheet" href="manage_users.css">
    <link rel="stylesheet" href="edit_user.css">
    <link rel="stylesheet" href="admin_layout.css">
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

<div class="edit-wrap">
    <div class="edit-card">
        <h1>Edit User (ID: <?= (int)$editUser['id'] ?>)</h1>

        <?php if (!empty($error)): ?>
            <div class="msg err"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="profile-preview">
            <?php
                $pic = $editUser['profile_picture'] ?? 'default.png';
                $path = "../uploads/" . $pic;
                if (!file_exists($path)) $path = "../uploads/default.png";
            ?>
            <img src="<?= htmlspecialchars($path) ?>" alt="Profile">
            <div>
                <div class="name"><?= htmlspecialchars($editUser['name']) ?></div>
                <div class="email"><?= htmlspecialchars($editUser['email']) ?></div>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($editUser['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($editUser['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="customer" <?= $editUser['role']==='customer'?'selected':'' ?>>Customer</option>
                    <option value="seller" <?= $editUser['role']==='seller'?'selected':'' ?>>Seller</option>
                    <option value="admin" <?= $editUser['role']==='admin'?'selected':'' ?>>Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" placeholder="Leave blank to keep unchanged">
            </div>

            <div class="form-group">
                <label>Profile Picture</label>
                <input type="file" name="profile_picture" accept="image/*">
            </div>

            <button class="btn-save" type="submit">Update User</button>
            <a class="btn-back" href="manage_users.php">Back</a>
        </form>
    </div>
</div>

</body>
</html>
