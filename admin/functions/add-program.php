<?php

// Include database connection
require_once '../../config.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve form data
    $program_name = $_POST['program_name'];
    $program_hour = $_POST['program_hour'];

    // Validate program name
    if (empty(trim($program_name))) {
        $msg = 'Please enter a program Name.';
        // Return the error message
        echo json_encode(['msg' => $msg]);
        exit();
    } else {
        // Check if program name already exists
        $sql_check_program = 'SELECT program_name FROM tbl_programs WHERE program_name = :program_name';
        $stmt_check_program = $pdo->prepare($sql_check_program);
        $stmt_check_program->bindParam(':program_name', $program_name, PDO::PARAM_STR);
        $stmt_check_program->execute();
        if ($stmt_check_program->rowCount() > 0) {
            $msg = 'Program Name is already taken.';
            // Return the error message
            echo json_encode(['msg' => $msg]);
            exit();
        }
    }

    if (empty(trim($program_name))) {
        $msg = 'Please enter a Program Name.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    if (empty(trim($program_hour))) {
        $msg = 'Please enter a Program Hour.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Generate a unique program ID
    $program_id = mt_rand(10000, 99999);

    // Prepare and execute the first SQL statement
    $sql = 'INSERT INTO tbl_programs (program_id, program_name, program_hour) VALUES (:program_id, :program_name, :program_hour)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':program_id', $program_id, PDO::PARAM_STR);
    $stmt->bindParam(':program_name', $program_name, PDO::PARAM_STR);
    $stmt->bindParam(':program_hour', $program_hour, PDO::PARAM_STR);
    
    if ($stmt->execute()) {       
        // Return success message
        $success = 'Program successfully created.';
        echo json_encode(['success' => $success]);
        exit();
    } else {
        // Return error message if something goes wrong
        $msg = 'Oops! Something went wrong. Please try again later.';
        echo json_encode(['msg' => $msg]);
        exit();
    }


}
?>
