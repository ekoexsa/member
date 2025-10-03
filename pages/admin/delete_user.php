<?php
// The main index.php handles session and database connection.

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $sql = "DELETE FROM users WHERE id = ? AND role = 'member'";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("i", $param_id);

        $param_id = trim($_GET["id"]);

        if($stmt->execute()){
            header("location: index.php?page=admin_manage_users");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    $stmt->close();

} else{
    // If no ID, redirect
    header("location: index.php?page=admin_manage_users");
    exit();
}
?>