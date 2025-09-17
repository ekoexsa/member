<?php
require_once '../includes/session.php';
check_login();
check_admin();

require_once '../config/database.php';

// Fetch pending donations
$sql = "SELECT d.id, u.nama_lengkap, p.nama_produk, d.jumlah_donasi, d.bukti_pembayaran, d.created_at
        FROM donations d
        JOIN users u ON d.user_id = u.id
        JOIN products p ON d.product_id = p.id
        WHERE d.status = 'pending'
        ORDER BY d.created_at DESC";
$result = $mysqli->query($sql);

$page_title = "Validate Donations";
?>

<?php include '../includes/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <?php include '../includes/sidebar_admin.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Validate Donations</h2>
        <?php
        if(isset($_SESSION['message'])){
            echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }
        ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Product</th>
                    <th>Amount</th>
                    <th>Proof</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                            <td>Rp <?php echo number_format($row['jumlah_donasi'], 2, ',', '.'); ?></td>
                            <td>
                                <?php if($row['bukti_pembayaran']): ?>
                                    <a href="../uploads/proof/<?php echo $row['bukti_pembayaran']; ?>" target="_blank">View Proof</a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <a href="update_donation_status.php?id=<?php echo $row['id']; ?>&status=validated" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to validate this donation?');">Validate</a>
                                <a href="update_donation_status.php?id=<?php echo $row['id']; ?>&status=rejected" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this donation?');">Reject</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No pending donations found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
