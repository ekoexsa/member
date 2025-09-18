<?php
// The main index.php file already handles session, database, and functions.
// We just need to fetch the data for this specific page.

// Fetch data for dashboard
$total_users = $mysqli->query("SELECT COUNT(id) as count FROM users WHERE role = 'member'")->fetch_assoc()['count'];
$total_products = $mysqli->query("SELECT COUNT(id) as count FROM products")->fetch_assoc()['count'];
$total_validated_donations = get_total_validated_donations($mysqli);

?>

<h2>Admin Dashboard</h2>
<p>Welcome, <?php echo htmlspecialchars($_SESSION["nama_lengkap"]); ?>!</p>
<hr>
<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Total Members</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $total_users; ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Total Products</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $total_products; ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">Validated Donations</div>
            <div class="card-body">
                <h5 class="card-title">Rp <?php echo number_format($total_validated_donations, 2, ',', '.'); ?></h5>
            </div>
        </div>
    </div>
</div>
