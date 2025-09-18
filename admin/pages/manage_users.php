<?php
// Fetch all members
$result = $mysqli->query("SELECT id, username, nama_lengkap, created_at FROM users WHERE role = 'member' ORDER BY created_at DESC");
?>

<h2>Manage Users</h2>
<a href="index.php?pg=add_user" class="btn btn-success mb-3">Add New Member</a>
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
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="index.php?pg=edit_user&id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="index.php?pg=delete_user&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
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
