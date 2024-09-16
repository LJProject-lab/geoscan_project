<?php
// Include database connection
require_once '../../config.php';
require_once 'action-ids.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $programId = isset($_POST['program_id']) ? $_POST['program_id'] : null;
    $programName = isset($_POST['program_name']) ? $_POST['program_name'] : null;
    $programHour = isset($_POST['program_hour']) ? $_POST['program_hour'] : null;





    // Validate program ID
    if (!$programId) {
        $msg = 'program ID is required.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Validate program name
    if (empty(trim($programName))) {
        $msg = 'Please enter a program name.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Validate program description
    if (empty(trim($programHour))) {
        $msg = 'Please enter a program description.';
        echo json_encode(['msg' => $msg]);
        exit();
    }
    // Check if program name already exists for the specified program
    $sql_check_program_name = 'SELECT COUNT(*) AS num_programs FROM tbl_programs WHERE program_id != :program_id AND program_name = :program_name';
    $stmt_check_program_name = $pdo->prepare($sql_check_program_name);
    $stmt_check_program_name->bindParam(':program_id', $programId, PDO::PARAM_INT);
    $stmt_check_program_name->bindParam(':program_name', $programName, PDO::PARAM_STR);
    $stmt_check_program_name->execute();
    $row = $stmt_check_program_name->fetch(PDO::FETCH_ASSOC);

    if ($row['num_programs'] > 0) {
        $msg = 'A program with the same name already exists for this program.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Prepare SQL statement to update program
    $sql = 'UPDATE tbl_programs SET program_name = :program_name, program_hour = :program_hour WHERE program_id = :program_id';
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':program_id', $programId, PDO::PARAM_INT);
    $stmt->bindParam(':program_name', $programName, PDO::PARAM_STR);
    $stmt->bindParam(':program_hour', $programHour, PDO::PARAM_STR);



    try {
        // Execute the prepared statement
        if ($stmt->execute()) {


            $sql_log = 'INSERT INTO tbl_actionlogs (user_id, action_id, action_desc) VALUES (:user_id, :action_id, :action_desc)';
            $stmt_log = $pdo->prepare($sql_log);
            $user_id = $_SESSION['admin_id'];
            $action_desc = 'Program ' . $programName . ' updated';
            $action_id = ACTION_UPDATE_PROGRAM;
            $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
            $stmt_log->execute();






            $success = 'program successfully updated!';
            echo json_encode(['success' => $success]);
            exit();
        } else {
            $msg = 'Oops! Something went wrong. Please try again later.';
            echo json_encode(['msg' => $msg]);
            exit();
        }
    } catch (PDOException $e) {
        $msg = 'Error: ' . $e->getMessage();
        echo json_encode(['msg' => $msg]);
        exit();
    }
} else {
    $msg = 'Invalid request method.';
    echo json_encode(['msg' => $msg]);
    exit();
}
?>