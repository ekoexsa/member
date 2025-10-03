<?php
$user_id = $_SESSION['id'];

// Fetch user's donation stats
$total_donations = $mysqli->query("SELECT COUNT(id) as count FROM donations WHERE user_id = $user_id")->fetch_assoc()['count'];
$validated_donations = $mysqli->query("SELECT COUNT(id) as count FROM donations WHERE user_id = $user_id AND status = 'validated'")->fetch_assoc()['count'];
$pending_donations = $mysqli->query("SELECT COUNT(id) as count FROM donations WHERE user_id = $user_id AND status = 'pending'")->fetch_assoc()['count'];
?>

<h2>User Dashboard</h2>
<p>Welcome, <?php echo htmlspecialchars($_SESSION["nama_lengkap"]); ?>!</p>
<hr>
<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">Total Donations</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $total_donations; ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Validated Donations</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $validated_donations; ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">Pending Donations</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $pending_donations; ?></h5>
            </div>
        </div>
    </div>
</div>
<div class="mt-4">
    <h4>Quick Actions</h4>
    <a href="index.php?page=user_make_donation" class="btn btn-primary">Make a New Donation</a>
    <a href="index.php?page=user_my_donations" class="btn btn-secondary">View My Donations</a>
</div>