<?php
$page_title = "My Profile";
include 'header.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php?page=login");
    exit;
}

require_once __DIR__ . '/../Model/Database.php';
require_once __DIR__ . '/../Model/User.php';

$db = new Database();
$user_model = new User($db);
$user = $user_model->getUserById($_SESSION['user']['id']);
?>

<div style="max-width: 600px; margin: 2rem auto;">
    <div class="card">
        <h1>My Profile</h1>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 4px; margin-bottom: 2rem;">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Account Type:</strong> <?php echo ucfirst($user['role']); ?></p>
            <p><strong>Member Since:</strong> <?php echo date('F j, Y', strtotime($user['created_at'] ?? 'now')); ?></p>
        </div>

        <h3>Update Profile</h3>
        <form method="POST" action="../Controller/profile-process.php">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 1rem;">Update Profile</button>
        </form>

        <hr style="margin: 2rem 0; border: none; border-top: 1px solid #eee;">

        <h3>Change Password</h3>
        <form method="POST" action="../Controller/change-password-process.php">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 1rem;">Change Password</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
