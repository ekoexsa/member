<?php
// The main index.php already started the session.
// This script just needs to clear and destroy it.

$_SESSION = array();

session_destroy();

// Redirect to the login page via the main router
header("location: index.php?page=login");
exit;
?>