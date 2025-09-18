<?php
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $donation_id = trim($_GET["id"]);
    $user_id = $_SESSION['id'];

    // First, get the file path to delete the file from server
    $sql_select = "SELECT bukti_pembayaran FROM donations WHERE id = ? AND user_id = ? AND status = 'pending'";
    if($stmt_select = $mysqli->prepare($sql_select)){
        $stmt_select->bind_param("ii", $donation_id, $user_id);
        if($stmt_select->execute()){
            $result = $stmt_select->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                if($row['bukti_pembayaran']){
                    $file_to_delete = "../../uploads/proof/" . $row['bukti_pembayaran']; // Adjusted path
                    if(file_exists($file_to_delete)){
                        unlink($file_to_delete); // Delete the file
                    }
                }
            } else {
                // If donation not found or not pending, just redirect
                header("location: index.php?pg=my_donations");
                exit();
            }
        }
        $stmt_select->close();
    }

    // Now, delete the record from the database
    $sql_delete = "DELETE FROM donations WHERE id = ? AND user_id = ?";

    if($stmt_delete = $mysqli->prepare($sql_delete)){
        $stmt_delete->bind_param("ii", $donation_id, $user_id);

        if($stmt_delete->execute()){
            header("location: index.php?pg=my_donations");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    $stmt_delete->close();

} else{
    header("location: index.php?pg=my_donations");
    exit();
}
?>
