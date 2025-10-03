<?php
// 1. Initialize
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// 2. Define Routes and Permissions
$routes = [
    // Guest pages
    'login' => ['path' => 'auth/login.php', 'access' => 'guest', 'layout' => true],
    'register' => ['path' => 'auth/register.php', 'access' => 'guest', 'layout' => true],

    // Shared pages (processing only)
    'logout' => ['path' => 'auth/logout.php', 'access' => 'auth', 'layout' => false],

    // Admin pages
    'admin_dashboard' => ['path' => 'admin/dashboard.php', 'access' => 'admin', 'layout' => true],
    'admin_manage_users' => ['path' => 'admin/manage_users.php', 'access' => 'admin', 'layout' => true],
    'admin_add_user' => ['path' => 'admin/add_user.php', 'access' => 'admin', 'layout' => true],
    'admin_edit_user' => ['path' => 'admin/edit_user.php', 'access' => 'admin', 'layout' => true],
    'admin_delete_user' => ['path' => 'admin/delete_user.php', 'access' => 'admin', 'layout' => false],
    'admin_manage_products' => ['path' => 'admin/manage_products.php', 'access' => 'admin', 'layout' => true],
    'admin_add_product' => ['path' => 'admin/add_product.php', 'access' => 'admin', 'layout' => true],
    'admin_edit_product' => ['path' => 'admin/edit_product.php', 'access' => 'admin', 'layout' => true],
    'admin_delete_product' => ['path' => 'admin/delete_product.php', 'access' => 'admin', 'layout' => false],
    'admin_validate_donations' => ['path' => 'admin/validate_donations.php', 'access' => 'admin', 'layout' => true],
    'admin_update_donation_status' => ['path' => 'admin/update_donation_status.php', 'access' => 'admin', 'layout' => false],

    // User pages
    'user_dashboard' => ['path' => 'user/dashboard.php', 'access' => 'member', 'layout' => true],
    'user_my_donations' => ['path' => 'user/my_donations.php', 'access' => 'member', 'layout' => true],
    'user_make_donation' => ['path' => 'user/make_donation.php', 'access' => 'member', 'layout' => true],
    'user_edit_donation' => ['path' => 'user/edit_donation.php', 'access' => 'member', 'layout' => true],
    'user_delete_donation' => ['path' => 'user/delete_donation.php', 'access' => 'member', 'layout' => false],
    'user_my_products' => ['path' => 'user/my_products.php', 'access' => 'member', 'layout' => true],
    'user_download_product' => ['path' => 'user/download_product.php', 'access' => 'member', 'layout' => false],
];

// 3. Determine the current page
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$user_role = $is_logged_in ? $_SESSION['role'] : null;

// Default page logic
$default_page = 'login';
if ($is_logged_in) {
    $default_page = ($user_role === 'admin') ? 'admin_dashboard' : 'user_dashboard';
}
$page_key = isset($_GET['page']) ? $_GET['page'] : $default_page;

// 4. Validate the page and user access
if (!array_key_exists($page_key, $routes)) {
    header("Location: index.php?page=$default_page");
    exit;
}

$current_page = $routes[$page_key];
$access_level = $current_page['access'];

// Check permissions
if ($access_level === 'guest' && $is_logged_in) {
    header("Location: index.php?page=$default_page");
    exit;
}
if ($access_level === 'auth' && !$is_logged_in) {
    header("Location: index.php?page=login");
    exit;
}
if ($access_level === 'admin' && (!$is_logged_in || $user_role !== 'admin')) {
    header("Location: index.php?page=login");
    exit;
}
if ($access_level === 'member' && (!$is_logged_in || $user_role !== 'member')) {
    header("Location: index.php?page=login");
    exit;
}

// 5. Render the page
$page_path = 'pages/' . $current_page['path'];
$has_layout = $current_page['layout'];

if ($has_layout) {
    // Render with full layout
    include 'includes/header.php';

    // The container is now part of the header/footer for better control
    // But we'll add it here for now.

    if ($access_level === 'admin' || $access_level === 'member') {
        // Admin or User page with sidebar
        echo '<div class="container mt-4"><div class="row">';
        echo '<div class="col-md-3">';
        if ($access_level === 'admin') {
            include 'includes/sidebar_admin.php';
        } else {
            include 'includes/sidebar_user.php';
        }
        echo '</div>';
        echo '<div class="col-md-9">';
    } else {
        // Guest page (login/register) without sidebar
        echo '<div class="container mt-4"><div class="row"><div class="col-md-6 offset-md-3">';
    }

    if (file_exists($page_path)) {
        include $page_path;
    } else {
        echo '<h2>Error 404: Page Not Found</h2>';
    }

    echo '</div></div></div>'; // Close cols, row, container
    include 'includes/footer.php';
} else {
    // Render without layout (for processing scripts like logout, delete, etc.)
    if (file_exists($page_path)) {
        include $page_path;
    } else {
        header("Location: index.php");
        exit;
    }
}
?>