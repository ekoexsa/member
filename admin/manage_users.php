<?php
require_once '../includes/session.php';
check_login();
check_admin();

require_once '../config/database.php';

// Fetch all members
$result = $mysqli->query("SELECT id, username, nama_lengkap, created_at FROM users WHERE role = 'member' ORDER BY created_at DESC");

$page_title = "Manage Users";
?>

<?php include '../includes/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <?php include '../includes/sidebar_admin.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Manage Users</h2>
        <a href="add_user.php" class="btn btn-success mb-3">Add New Member</a>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Registered At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No members found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
