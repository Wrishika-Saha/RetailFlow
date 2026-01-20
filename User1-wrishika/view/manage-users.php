<?php
$page_title = "Manage Users";
include 'header.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . '/../Model/Database.php';
require_once __DIR__ . '/../Model/User.php';

$db = new Database();
$user_model = new User($db);
$users = $user_model->getAllUsers();
?>

<div style="margin-bottom: 2rem;">
    <h1>Manage Users</h1>
    <a href="index.php?page=admin-dashboard" class="btn secondary" style="text-decoration: none;">← Back to Dashboard</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span style="background: <?php echo $user['role'] === 'admin' ? '#667eea' : ($user['role'] === 'seller' ? '#ff6b6b' : '#28a745'); ?>; color: white; padding: 0.25rem 0.75rem; border-radius: 4px;">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?page=user-detail&id=<?php echo $user['id']; ?>" class="btn" style="padding: 0.5rem 1rem; text-decoration: none; font-size: 13px;">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
