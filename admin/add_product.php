<?php
require_once '../includes/session.php';
check_login();
check_admin();

require_once '../config/database.php';
require_once '../includes/functions.php';

$nama_produk = $deskripsi = $harga_donasi = "";
$nama_produk_err = $deskripsi_err = $harga_donasi_err = $file_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = sanitize_input($_POST['nama_produk']);
    $deskripsi = sanitize_input($_POST['deskripsi']);
    $harga_donasi = sanitize_input($_POST['harga_donasi']);

    // Validate form inputs
    if (empty($nama_produk)) $nama_produk_err = "Please enter product name.";
    if (empty($deskripsi)) $deskripsi_err = "Please enter a description.";
    if (empty($harga_donasi) || !is_numeric($harga_donasi)) $harga_donasi_err = "Please enter a valid donation price.";

    // File upload handling
    if (isset($_FILES["product_file"]) && $_FILES["product_file"]["error"] == 0) {
        $target_dir = "../uploads/";
        $file_name = basename($_FILES["product_file"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // You might want to add more checks here (file size, file type, etc.)
        if (file_exists($target_file)) {
            $file_err = "Sorry, file already exists.";
        }

        if (empty($file_err)) {
            if (!move_uploaded_file($_FILES["product_file"]["tmp_name"], $target_file)) {
                $file_err = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $file_err = "Please select a file to upload.";
    }

    if (empty($nama_produk_err) && empty($deskripsi_err) && empty($harga_donasi_err) && empty($file_err)) {
        $sql = "INSERT INTO products (nama_produk, deskripsi, harga_donasi, file_path) VALUES (?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ssds", $nama_produk, $deskripsi, $harga_donasi, $file_name);
            if ($stmt->execute()) {
                header("location: manage_products.php");
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
        <?php include '../includes/sidebar_admin.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Add New Product</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <label>Product Name</label>
                <input type="text" name="nama_produk" class="form-control <?php echo (!empty($nama_produk_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nama_produk; ?>">
                <span class="invalid-feedback"><?php echo $nama_produk_err; ?></span>
            </div>
            <div class="form-group mb-3">
                <label>Description</label>
                <textarea name="deskripsi" class="form-control <?php echo (!empty($deskripsi_err)) ? 'is-invalid' : ''; ?>"><?php echo $deskripsi; ?></textarea>
                <span class="invalid-feedback"><?php echo $deskripsi_err; ?></span>
            </div>
            <div class="form-group mb-3">
                <label>Donation Price (Rp)</label>
                <input type="number" step="0.01" name="harga_donasi" class="form-control <?php echo (!empty($harga_donasi_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $harga_donasi; ?>">
                <span class="invalid-feedback"><?php echo $harga_donasi_err; ?></span>
            </div>
            <div class="form-group mb-3">
                <label>Product File</label>
                <input type="file" name="product_file" class="form-control <?php echo (!empty($file_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $file_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Add Product">
                <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
