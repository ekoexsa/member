<?php
require_once '../includes/session.php';
check_login();
check_admin();

require_once '../config/database.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $donation_id = trim($_GET['id']);
    $status = trim($_GET['status']);

    if ($status == 'validated' || $status == 'rejected') {

        $sql = "UPDATE donations SET status = ?, validated_at = ? WHERE id = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $validated_at = ($status == 'validated') ? date('Y-m-d H:i:s') : null;
            $stmt->bind_param("ssi", $status, $validated_at, $donation_id);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Donation status has been updated successfully.";
                header("location: validate_donations.php");
                exit();
            } else {
                echo "Error updating record: " . $mysqli->error;
            }
            $stmt->close();
        }
    } else {
        echo "Invalid status value.";
    }
    $mysqli->close();
} else {
    echo "Required parameters are missing.";
}
?>
