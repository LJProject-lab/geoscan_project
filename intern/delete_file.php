<?php
// delete_file.php

include '../config.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileId = $_POST['file_id'];

    // Validate file ID and delete the file from the database
    $stmt = $pdo->prepare("DELETE FROM tbl_requirements WHERE id = :file_id");
    $stmt->bindParam(':file_id', $fileId);
    $success = $stmt->execute();

    // Prepare JSON response
    header('Content-Type: application/json');
    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'File has been deleted.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete the file.']);
    }
}
?>
