<?php
require_once '../includes/session.php';
check_login();
check_admin();

require_once '../config/database.php';

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $product_id = trim($_GET["id"]);

    // First, get the file path to delete the file from server
    $sql_select = "SELECT file_path FROM products WHERE id = ?";
    if($stmt_select = $mysqli->prepare($sql_select)){
        $stmt_select->bind_param("i", $product_id);
        if($stmt_select->execute()){
            $result = $stmt_select->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $file_to_delete = "../uploads/" . $row['file_path'];
                if(file_exists($file_to_delete)){
                    unlink($file_to_delete); // Delete the file
                }
            }
        }
        $stmt_select->close();
    }

    // Now, delete the record from the database
    $sql_delete = "DELETE FROM products WHERE id = ?";

    if($stmt_delete = $mysqli->prepare($sql_delete)){
        $stmt_delete->bind_param("i", $product_id);

        if($stmt_delete->execute()){
            header("location: manage_products.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    $stmt_delete->close();

    $mysqli->close();
} else{
    if(empty(trim($_GET["id"]))){
        echo "ID parameter is missing.";
    }
    exit();
}
?>
