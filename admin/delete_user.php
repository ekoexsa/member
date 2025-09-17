<?php
require_once '../includes/session.php';
check_login();
check_admin();

require_once '../config/database.php';

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $sql = "DELETE FROM users WHERE id = ? AND role = 'member'";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("i", $param_id);

        $param_id = trim($_GET["id"]);

        if($stmt->execute()){
            header("location: manage_users.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    $stmt->close();

    $mysqli->close();
} else{
    if(empty(trim($_GET["id"]))){
        echo "ID parameter is missing.";
    }
    exit();
}
?>
