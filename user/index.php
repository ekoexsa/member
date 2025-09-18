<?php
require_once '../includes/session.php';
check_login();
check_member();

require_once '../config/database.php';
require_once '../includes/functions.php';

// Define the pages that are allowed
$allowed_pages = [
    'dashboard',
    'my_donations',
    'make_donation',
    'edit_donation',
    'delete_donation',
    'my_products',
    'download_product'
];

// Get the requested page, default to 'dashboard'
$page = isset($_GET['pg']) ? $_GET['pg'] : 'dashboard';

// Check if the requested page is in the allowed list
if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}

// Construct the file path
$page_file = 'pages/' . $page . '.php';

// Pages that don't need the HTML layout
$no_layout_pages = ['delete_donation', 'download_product'];

if (in_array($page, $no_layout_pages)) {
    if (file_exists($page_file)) {
        include $page_file;
    }
    exit();
}

// Include the header for pages with layout
include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include '../includes/sidebar_user.php'; ?>
    </div>
    <div class="col-md-9">
        <?php
        // Include the page file if it exists
        if (file_exists($page_file)) {
            include $page_file;
        } else {
            echo "<h2>Error 404: Page Not Found</h2>";
        }
        ?>
    </div>
</div>

<?php
// Include the footer
include '../includes/footer.php';
?>
