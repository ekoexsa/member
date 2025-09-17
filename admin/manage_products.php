<?php
require_once '../includes/session.php';
check_login();
check_admin();

require_once '../config/database.php';

// Fetch all products
$result = $mysqli->query("SELECT id, nama_produk, harga_donasi, created_at FROM products ORDER BY created_at DESC");

$page_title = "Manage Products";
?>

<?php include '../includes/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <?php include '../includes/sidebar_admin.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Manage Products</h2>
        <a href="add_product.php" class="btn btn-success mb-3">Add New Product</a>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Donation Price</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                            <td>Rp <?php echo number_format($row['harga_donasi'], 2, ',', '.'); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
