<?php
// Logic for editing a pending donation.

$jumlah_donasi = $product_info = "";
$jumlah_donasi_err = $file_err = "";
$donation_id = 0;

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $donation_id = trim($_GET["id"]);
    $user_id = $_SESSION['id'];

    $sql = "SELECT d.jumlah_donasi, p.nama_produk, p.harga_donasi
            FROM donations d
            JOIN products p ON d.product_id = p.id
            WHERE d.id = ? AND d.user_id = ? AND d.status = 'pending'";
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("ii", $donation_id, $user_id);
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $jumlah_donasi = $row['jumlah_donasi'];
                $product_info = htmlspecialchars($row['nama_produk']) . " (Min. Donation: Rp " . number_format($row['harga_donasi'], 2, ',', '.') . ")";
            } else {
                header("location: index.php?page=user_my_donations");
                exit();
            }
        } else {
            echo "Oops! Something went wrong.";
            exit();
        }
        $stmt->close();
    }
} else {
    header("location: index.php?page=user_my_donations");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donation_id = $_POST['id'];
    $jumlah_donasi = sanitize_input($_POST['jumlah_donasi']);

    if (empty($jumlah_donasi) || !is_numeric($jumlah_donasi)) $jumlah_donasi_err = "Please enter a valid donation amount.";

    $file_name = "";
    if (isset($_FILES["bukti_pembayaran"]) && $_FILES["bukti_pembayaran"]["error"] == 0) {
        $target_dir = "uploads/proof/";
        $file_name = time() . "_" . basename($_FILES["bukti_pembayaran"]["name"]);
        $target_file = $target_dir . $file_name;

        if (!move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $target_file)) {
            $file_err = "Sorry, there was an error uploading your new file.";
        }
    }

    if (empty($jumlah_donasi_err) && empty($file_err)) {
        if (!empty($file_name)) {
            $sql = "UPDATE donations SET jumlah_donasi = ?, bukti_pembayaran = ? WHERE id = ? AND user_id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("dsii", $jumlah_donasi, $file_name, $donation_id, $_SESSION['id']);
        } else {
            $sql = "UPDATE donations SET jumlah_donasi = ? WHERE id = ? AND user_id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("dii", $jumlah_donasi, $donation_id, $_SESSION['id']);
        }

        if ($stmt->execute()) {
            header("location: index.php?page=user_my_donations");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}
?>

<h2>Edit Donation</h2>
<form action="index.php?page=user_edit_donation&id=<?php echo $donation_id; ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $donation_id; ?>"/>
    <div class="form-group mb-3">
        <label>Product</label>
        <input type="text" class="form-control" value="<?php echo $product_info; ?>" readonly>
    </div>
    <div class="form-group mb-3">
        <label>Donation Amount (Rp)</label>
        <input type="number" step="0.01" name="jumlah_donasi" class="form-control <?php echo (!empty($jumlah_donasi_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $jumlah_donasi; ?>">
        <span class="invalid-feedback"><?php echo $jumlah_donasi_err; ?></span>
    </div>
    <div class="form-group mb-3">
        <label>New Proof of Payment (Optional)</label>
        <input type="file" name="bukti_pembayaran" class="form-control <?php echo (!empty($file_err)) ? 'is-invalid' : ''; ?>">
        <span class="invalid-feedback"><?php echo $file_err; ?></span>
        <small class="form-text text-muted">Leave blank if you don't want to change the proof.</small>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Update Donation">
        <a href="index.php?page=user_my_donations" class="btn btn-secondary">Cancel</a>
    </div>
</form>