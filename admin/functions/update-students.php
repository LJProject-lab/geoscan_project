<?php
// Include database connection
require_once '../../config.php';
require_once 'action-ids.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $studentId = isset($_POST['student_id']) ? $_POST['student_id'] : null;
    $availability = isset($_POST['availability']) ? $_POST['availability'] : null;

    // Fetch name 
    $sql_fetch = "SELECT firstname, lastname FROM tbl_users WHERE student_id = :student_id";
    $stmt_fetch = $pdo->prepare($sql_fetch);
    $stmt_fetch->bindParam(':student_id', $studentId, PDO::PARAM_STR);
    $stmt_fetch->execute();

    $student = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
    if (!$student) {
        // If the student doesn't exist, rollback and exit
        $pdo->rollBack();
        echo json_encode(['msg' => 'Student not found']);
        exit;
    }

    // Validate student ID
    if (!$studentId) {
        $msg = 'Student ID is required.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    
    $firstname = $student['firstname'];
    $lastname = $student['lastname'];

    // Prepare SQL statement to update student
    $sql = 'UPDATE tbl_users SET  status = :availability WHERE student_id = :student_id';
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':availability', $availability, PDO::PARAM_STR);
    $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);

    try {

        // Execute the prepared statement
        if ($stmt->execute()) {

            $sql_log = 'INSERT INTO tbl_actionlogs (user_id, action_id, action_desc) VALUES (:user_id, :action_id, :action_desc)';
            $stmt_log = $pdo->prepare($sql_log);
            $user_id = $_SESSION['admin_id'];

            $action_desc = "Status Set " . ($availability == "Active" ? "Active" : "Inactive") . " for Student " . $firstname . ' ' . $lastname;;

            $action_id = ACTION_SET_STATUS_STUDENT;
            $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
            $stmt_log->execute();

            $success = 'Student successfully updated!';
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