<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
function check_login() {
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: ../auth/login.php");
        exit;
    }
}

function check_admin() {
    if($_SESSION["role"] !== 'admin'){
        // Redirect to user dashboard or an error page
        header("location: ../user/dashboard.php");
        exit;
    }
}

function check_member() {
    if($_SESSION["role"] !== 'member'){
        // Redirect to admin dashboard or an error page
        header("location: ../admin/dashboard.php");
        exit;
    }
}
?>
