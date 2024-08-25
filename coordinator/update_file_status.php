<?php
require 'config.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['file_id'])) {
        $file_id = $_POST['file_id'];
        $status = '';

        // Determine which button was clicked
        if (isset($_POST['approve'])) {
            $status = 'Approved';
        } elseif (isset($_POST['cancel'])) {
            $status = 'Cancelled';
        }

        // Update the file status in the database
        if ($status) {
            $stmt = $pdo->prepare("UPDATE tbl_requirements SET status = :status WHERE id = :file_id");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':file_id', $file_id);
            $stmt->execute();
        }
    }
}

// Redirect back to the previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
