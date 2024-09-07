<?php

// Include database connection
require_once '../config.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve form data
    $reason = $_POST['reason'];
    $dates = $_POST['dates'];
    $records = $dates;
    $student_id = $_POST['student_id']; 
    $status = 'Pending'; // Define status as a variable

    // Check if reason is empty
    if (empty(trim($reason))) {
        $msg = 'Please enter a Reason.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Prepare and execute the SQL statement
    $sql = 'INSERT INTO tbl_adjustments (student_id, records, status, reason) VALUES (:student_id, :records, :status, :reason)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR); // Bind student ID
    $stmt->bindParam(':records', $records, PDO::PARAM_STR); // Bind dates (records)
    $stmt->bindParam(':status', $status, PDO::PARAM_STR); // Bind status (as a variable)
    $stmt->bindParam(':reason', $reason, PDO::PARAM_STR); // Bind reason

    if ($stmt->execute()) {
        // Return success message
        $success = 'Adjustments successfully sent.';
        echo json_encode(['success' => $success]);
        exit();
    } else {
        // Return error message if something goes wrong
        $msg = 'Oops! Something went wrong. Please try again later.';
        echo json_encode(['msg' => $msg]);
        exit();
    }
}
