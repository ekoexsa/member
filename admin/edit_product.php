<?php
require_once '../includes/session.php';
check_login();
check_admin();

require_once '../config/database.php';
require_once '../includes/functions.php';

$nama_produk = $deskripsi = $harga_donasi = "";
$nama_produk_err = $deskripsi_err = $harga_donasi_err = "";
$product_id = 0;

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $product_id = trim($_GET["id"]);

    $sql = "SELECT nama_produk, deskripsi, harga_donasi FROM products WHERE id = ?";
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("i", $param_id);
        $param_id = $product_id;

        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $nama_produk = $row['nama_produk'];
                $deskripsi = $row['deskripsi'];
                $harga_donasi = $row['harga_donasi'];
            } else {
                echo "Product not found.";
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
} else {
    echo "ID parameter is missing.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['id'];
    $nama_produk = sanitize_input($_POST['nama_produk']);
    $deskripsi = sanitize_input($_POST['deskripsi']);
    $harga_donasi = sanitize_input($_POST['harga_donasi']);

    if (empty($nama_produk)) $nama_produk_err = "Please enter product name.";
    if (empty($deskripsi)) $deskripsi_err = "Please enter a description.";
    if (empty($harga_donasi) || !is_numeric($harga_donasi)) $harga_donasi_err = "Please enter a valid donation price.";

    if (empty($nama_produk_err) && empty($deskripsi_err) && empty($harga_donasi_err)) {
        $sql = "UPDATE products SET nama_produk = ?, deskripsi = ?, harga_donasi = ? WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ssdi", $nama_produk, $deskripsi, $harga_donasi, $product_id);
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
        <h2>Edit Product</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $product_id; ?>"/>
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
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Update Product">
                <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
