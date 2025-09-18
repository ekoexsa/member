<?php
// Function to sanitize user input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to calculate total validated donations
function get_total_validated_donations($mysqli) {
    $sql = "SELECT SUM(jumlah_donasi) as total FROM donations WHERE status = 'validated'";
    if ($result = $mysqli->query($sql)) {
        $row = $result->fetch_assoc();
        return $row['total'] ? $row['total'] : 0;
    }
    return 0;
}
?>
