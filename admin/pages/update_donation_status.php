<?php
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
                header("location: index.php?pg=validate_donations");
                exit();
            } else {
                echo "Error updating record: " . $mysqli->error;
            }
            $stmt->close();
        }
    } else {
        // Invalid status, redirect
        header("location: index.php?pg=validate_donations");
        exit();
    }
} else {
    // Missing parameters, redirect
    header("location: index.php?pg=validate_donations");
    exit();
}
?>
