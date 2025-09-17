<?php
require_once '../includes/session.php';
check_login();
check_member();

require_once '../config/database.php';
require_once '../includes/functions.php';

$product_id = $jumlah_donasi = "";
$product_id_err = $jumlah_donasi_err = $file_err = "";

// Fetch products for the dropdown
$products_result = $mysqli->query("SELECT id, nama_produk, harga_donasi FROM products");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id'];
    $product_id = sanitize_input($_POST['product_id']);
    $jumlah_donasi = sanitize_input($_POST['jumlah_donasi']);

    if (empty($product_id)) $product_id_err = "Please select a product.";
    if (empty($jumlah_donasi) || !is_numeric($jumlah_donasi)) $jumlah_donasi_err = "Please enter a valid donation amount.";

    // File upload handling for payment proof
    if (isset($_FILES["bukti_pembayaran"]) && $_FILES["bukti_pembayaran"]["error"] == 0) {
        $target_dir = "../uploads/proof/";
        $file_name = time() . "_" . basename($_FILES["bukti_pembayaran"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = array("jpg", "jpeg", "png", "pdf");
        if (!in_array($file_type, $allowed_types)) {
            $file_err = "Sorry, only JPG, JPEG, PNG & PDF files are allowed.";
        }

        if (empty($file_err)) {
            if (!move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $target_file)) {
                $file_err = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $file_err = "Please upload proof of payment.";
    }

    if (empty($product_id_err) && empty($jumlah_donasi_err) && empty($file_err)) {
        $sql = "INSERT INTO donations (user_id, product_id, jumlah_donasi, bukti_pembayaran) VALUES (?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("iids", $user_id, $product_id, $jumlah_donasi, $file_name);
            if ($stmt->execute()) {
                header("location: my_donations.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    $mysqli->close();
}
?>

<?php include '../includes/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <?php include '../includes/sidebar_user.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Make a Donation</h2>
        <p>Select a product and enter your donation details.</p>
        <div class="alert alert-info">
            <strong>Payment Instructions:</strong> Please transfer the donation amount via Dana to <strong>081234567890</strong> (Example). Upload the screenshot as proof.
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <label>Product</label>
                <select name="product_id" class="form-control <?php echo (!empty($product_id_err)) ? 'is-invalid' : ''; ?>">
                    <option value="">Select a Product</option>
                    <?php while($product = $products_result->fetch_assoc()): ?>
                        <option value="<?php echo $product['id']; ?>" data-price="<?php echo $product['harga_donasi']; ?>">
                            <?php echo htmlspecialchars($product['nama_produk']); ?> (Min. Donation: Rp <?php echo number_format($product['harga_donasi'], 2, ',', '.'); ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
                <span class="invalid-feedback"><?php echo $product_id_err; ?></span>
            </div>
            <div class="form-group mb-3">
                <label>Donation Amount (Rp)</label>
                <input type="number" step="0.01" name="jumlah_donasi" class="form-control <?php echo (!empty($jumlah_donasi_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $jumlah_donasi; ?>">
                <span class="invalid-feedback"><?php echo $jumlah_donasi_err; ?></span>
            </div>
            <div class="form-group mb-3">
                <label>Proof of Payment (Screenshot)</label>
                <input type="file" name="bukti_pembayaran" class="form-control <?php echo (!empty($file_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $file_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit Donation">
                <a href="my_donations.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
