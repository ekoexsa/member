<?php
$user_id = $_SESSION['id'];

// Fetch user's donations
$sql = "SELECT d.id, p.nama_produk, d.jumlah_donasi, d.status, d.created_at
        FROM donations d
        JOIN products p ON d.product_id = p.id
        WHERE d.user_id = ?
        ORDER BY d.created_at DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>My Donations</h2>
<a href="index.php?pg=make_donation" class="btn btn-success mb-3">Make a New Donation</a>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Product</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                    <td>Rp <?php echo number_format($row['jumlah_donasi'], 2, ',', '.'); ?></td>
                    <td>
                        <span class="badge bg-<?php
                            switch($row['status']){
                                case 'validated': echo 'success'; break;
                                case 'rejected': echo 'danger'; break;
                                default: echo 'warning';
                            }
                        ?>"><?php echo ucfirst($row['status']); ?></span>
                    </td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <?php if($row['status'] == 'pending'): ?>
                            <a href="index.php?pg=edit_donation&id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="index.php?pg=delete_donation&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">You have not made any donations yet.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
