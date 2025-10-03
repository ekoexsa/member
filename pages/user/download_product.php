<?php
// Processing script for downloading a product.

if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $product_id = trim($_GET['id']);
    $user_id = $_SESSION['id'];

    // Verify user's eligibility to download this product
    $sql = "SELECT p.file_path
            FROM products p
            JOIN donations d ON p.id = d.product_id
            WHERE d.user_id = ?
            AND p.id = ?
            AND d.status = 'validated'
            AND d.jumlah_donasi >= p.harga_donasi
            LIMIT 1";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Path is now relative to the root index.php
            $file_path = 'uploads/' . $row['file_path'];

            if (file_exists($file_path)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file_path));
                flush(); // Flush system output buffer
                readfile($file_path);
                exit;
            } else {
                http_response_code(404);
                echo "File not found.";
            }
        } else {
            http_response_code(403);
            echo "You are not authorized to download this file.";
        }
        $stmt->close();
    }
} else {
    http_response_code(400);
    echo "No file specified.";
}
?>