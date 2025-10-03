<?php
$user_id = $_SESSION['id'];

// Fetch products the user is eligible to download
$sql = "SELECT DISTINCT p.id, p.nama_produk, p.deskripsi, p.file_path
        FROM products p
        JOIN donations d ON p.id = d.product_id
        WHERE d.user_id = ?
        AND d.status = 'validated'
        AND d.jumlah_donasi >= p.harga_donasi";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>My Products</h2>
<p>Here are the products you are eligible to download.</p>
<div class="list-group">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?php echo htmlspecialchars($row['nama_produk']); ?></h5>
                    <a href="index.php?page=user_download_product&id=<?php echo $row['id']; ?>" class="btn btn-success">Download</a>
                </div>
                <p class="mb-1"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">You have no products available for download yet. Make a validated donation that meets the product's price to get access.</p>
    <?php endif; ?>
</div>