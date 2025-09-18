<?php
require_once '../includes/session.php';
check_login();
check_admin();

require_once '../config/database.php';
require_once '../includes/functions.php';

// Define the pages that are allowed
$allowed_pages = [
    'dashboard',
    'manage_users',
    'add_user',
    'edit_user',
    'delete_user',
    'manage_products',
    'add_product',
    'edit_product',
    'delete_product',
    'validate_donations',
    'update_donation_status'
];

// Get the requested page, default to 'dashboard'
$page = isset($_GET['pg']) ? $_GET['pg'] : 'dashboard';

// Check if the requested page is in the allowed list
if (!in_array($page, $allowed_pages)) {
    // If not allowed, redirect to dashboard or show a 404 error
    $page = 'dashboard';
}

// Construct the file path
$page_file = 'pages/' . $page . '.php';

// For pages that are just processing and redirecting, we shouldn't include header/footer
$no_layout_pages = ['delete_user', 'delete_product', 'update_donation_status'];

if (in_array($page, $no_layout_pages)) {
    if (file_exists($page_file)) {
        include $page_file;
    }
    // No further output
    exit();
}

// Include the header for pages with layout
include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include '../includes/sidebar_admin.php'; ?>
    </div>
    <div class="col-md-9">
        <?php
        // Include the page file if it exists
        if (file_exists($page_file)) {
            include $page_file;
        } else {
            // A fallback if the file doesn't exist, though the in_array check should prevent this
            echo "<h2>Error 404: Page Not Found</h2>";
            echo "<p>The requested page could not be found.</p>";
        }
        ?>
    </div>
</div>

<?php
// Include the footer
include '../includes/footer.php';
?>
