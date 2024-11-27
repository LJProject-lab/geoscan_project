<?php
require 'config.php'; // Include your database connection file
require_once 'action-ids.php';
include "crypt_helper.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the file ID, student ID, and the action (approve or cancel)
    $file_id = $_POST['file_id'];
    $student_id = $_POST['student_id'];

    // Check if the action is approve or cancel
    if (isset($_POST['approve'])) {
        // Update the file status to "Approved"
        $stmt = $pdo->prepare("UPDATE tbl_requirements SET status = :status WHERE id = :file_id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':file_id', $file_id);
        $status = 'Approved';  // Set status as Approved
        $stmt->execute();
        
        // Redirect back to the page
        header("Location: view_intern_requirement.php?student_id=" . encryptData($student_id));
        exit;
    }

    if (isset($_POST['cancel'])) {
        // Update the file status to "Cancelled" and add the cancellation reason
        $cancel_reason = $_POST['cancel_reason'];
        $stmt = $pdo->prepare("UPDATE tbl_requirements SET status = :status, cancel_reason = :cancel_reason WHERE id = :file_id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':cancel_reason', $cancel_reason);
        $stmt->bindParam(':file_id', $file_id);
        $status = 'Cancelled';  // Set status as Cancelled
        $stmt->execute();
        
        // Redirect back to the page
        header("Location: view_intern_requirement.php?student_id=" . encryptData($student_id));
        exit;
    }
}
?>
