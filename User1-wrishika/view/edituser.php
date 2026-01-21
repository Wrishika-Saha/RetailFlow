<?php include('../Model/edituser.php'); ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="manage_users.css"> 
    <style>
        /* small page-specific styling */
        .edit-wrap{
            margin-left:260px;
            padding:30px;
        }
        .edit-card{
            max-width:700px;
            background:#fff;
            border-radius:16px;
            padding:22px;
            box-shadow:0 8px 22px rgba(0,0,0,0.08);
        }
        .edit-card h1{font-size:22px;font-weight:900;margin-bottom:14px;}
        .form-group{margin-bottom:12px;}
        label{display:block;font-weight:800;margin-bottom:6px;}
        input, select{
            width:100%;
            padding:10px 12px;
            border:1px solid #e5e7eb;
            border-radius:10px;
            outline:none;
        }
        input:focus, select:focus{border-color:#2563eb;}
        .btn-save{
            display:inline-block;
            padding:10px 14px;
            border:none;
            border-radius:10px;
            background:#2563eb;
            color:#fff;
            font-weight:900;
            cursor:pointer;
        }
        .btn-save:hover{background:#1d4ed8;}
        .btn-back{
            display:inline-block;
            margin-left:10px;
            text-decoration:none;
            padding:10px 14px;
            border-radius:10px;
            background:#111827;
            color:#fff;
            font-weight:900;
        }
        .msg{
            padding:10px 12px;
            border-radius:10px;
            margin-bottom:12px;
            font-weight:800;
        }
        .msg.err{background:#fee2e2;color:#991b1b;border:1px solid #fecaca;}
        .msg.ok{background:#dcfce7;color:#166534;border:1px solid #bbf7d0;}
        .profile-preview{
            display:flex;
            gap:12px;
            align-items:center;
            margin:10px 0 14px;
        }
        .profile-preview img{
            width:64px;height:64px;border-radius:50%;
            object-fit:cover;border:2px solid #e5e7eb;
        }

        @media(max-width:900px){
            .edit-wrap{margin-left:0;padding:16px;}
        }
    </style>
</head>
<body>

<a href="../Controller/logout.php" class="logout-btn">Logout</a>

<!-- Sidebar (same as Manage Users page) -->
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

        <?php if ($error): ?>
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
                <div style="font-weight:900;"><?= htmlspecialchars($editUser['name']) ?></div>
                <div style="color:#6b7280;font-size:13px;"><?= htmlspecialchars($editUser['email']) ?></div>
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
                    <option value="customer" <?= $editUser['role'] === 'customer' ? 'selected' : '' ?>>customer</option>
                    <option value="seller"   <?= $editUser['role'] === 'seller' ? 'selected' : '' ?>>seller</option>
                    <option value="admin"    <?= $editUser['role'] === 'admin' ? 'selected' : '' ?>>admin</option>
                </select>
            </div>

            <div class="form-group">
                <label>New Password (leave blank to keep unchanged)</label>
                <input type="password" name="password" placeholder="Enter new password (optional)">
            </div>

            <div class="form-group">
                <label>Profile Picture (optional)</label>
                <input type="file" name="profile_picture" accept="image/*">
            </div>

            <button class="btn-save" type="submit">Update User</button>
            <a class="btn-back" href="manage_users.php">Back</a>
        </form>
    </div>
</div>

</body>
</html>
