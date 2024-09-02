<?php
// Include database connection
require_once '../../config.php';
require_once 'action-ids.php';


// Process delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve student_id from POST data
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : null;

    if (!$student_id) {
        $msg = 'Student ID is missing.';
        echo json_encode(['msg' => $msg]);
        exit;
    }

    try {
        // Start a database transaction
        $pdo->beginTransaction();

        // Fetch name 
        $sql_fetch = "SELECT firstname, lastname FROM tbl_users WHERE student_id = :student_id";
        $stmt_fetch = $pdo->prepare($sql_fetch);
        $stmt_fetch->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $stmt_fetch->execute();

        $student = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
        if (!$student) {
            // If the student doesn't exist, rollback and exit
            $pdo->rollBack();
            echo json_encode(['msg' => 'Student not found']);
            exit;
        }

        $firstname = $student['firstname'];
        $lastname = $student['lastname'];

        // Delete the user record from the database
        $sql_delete_user = "DELETE FROM tbl_users WHERE student_id = :student_id";
        $stmt_delete_user = $pdo->prepare($sql_delete_user);
        $stmt_delete_user->bindParam(':student_id', $student_id, PDO::PARAM_INT);

        // Execute the SQL statement to delete the user
        if ($stmt_delete_user->execute()) {
            $action_id = ACTION_DELETE_STUDENT;

            $sql_log = 'INSERT INTO tbl_actionlogs (user_id, action_id, action_desc) VALUES (:user_id, :action_id, :action_desc)';
            $stmt_log = $pdo->prepare($sql_log);
            $user_id = $_SESSION['admin_id'];
            $action_desc = 'Deleted student ' . $firstname . ' ' . $lastname;
            $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT); // Using variable
            $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
            $stmt_log->execute();

            $pdo->commit();
            echo json_encode(['success' => 'Student deleted successfully']);
            exit;


        } else {
            // Rollback the transaction if deletion of the user fails
            $pdo->rollBack();
            echo json_encode(['msg' => 'Failed to delete user']);
            exit;
        }
    } catch (PDOException $e) {
        // Rollback the transaction and return error message if an exception occurs
        $pdo->rollBack();
        echo json_encode(['msg' => $e->getMessage()]);
        exit;
    }
} else {
    // Return error response if request method is not POST
    echo json_encode(['msg' => 'Invalid request method']);
    exit;
}
?>