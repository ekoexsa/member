<?php
// Fetch all products
$result = $mysqli->query("SELECT id, nama_produk, harga_donasi, created_at FROM products ORDER BY created_at DESC");
?>

<h2>Manage Products</h2>
<a href="index.php?pg=add_product" class="btn btn-success mb-3">Add New Product</a>
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
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                    <td>Rp <?php echo number_format($row['harga_donasi'], 2, ',', '.'); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="index.php?pg=edit_product&id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="index.php?pg=delete_product&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
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
