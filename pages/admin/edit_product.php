<?php
// Logic for editing product details.

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
                header("location: index.php?page=admin_manage_products");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
} else {
    header("location: index.php?page=admin_manage_products");
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
                header("location: index.php?page=admin_manage_products");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
}
?>

<h2>Edit Product</h2>
<form action="index.php?page=admin_edit_product&id=<?php echo $product_id; ?>" method="post">
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
        <a href="index.php?page=admin_manage_products" class="btn btn-secondary">Cancel</a>
    </div>
</form>