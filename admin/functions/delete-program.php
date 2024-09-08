<?php
// Include database connection
require_once '../../config.php';
require_once 'action-ids.php';

// Process delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve program_id from POST data
    $program_id = isset($_POST['program_id']) ? $_POST['program_id'] : null;

    if (!$program_id) {
        $msg = 'Program ID is missing.';
        echo json_encode(['msg' => $msg]);
        exit;
    }

    try {
        // Start a database transaction
        $pdo->beginTransaction();

        // Fetch name 
        $sql_fetch = "SELECT program_name FROM tbl_programs WHERE program_id = :program_id";
        $stmt_fetch = $pdo->prepare($sql_fetch);
        $stmt_fetch->bindParam(':program_id', $program_id, PDO::PARAM_STR);
        $stmt_fetch->execute();

        $program = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
        if (!$program) {
            // If the program doesn't exist, rollback and exit
            $pdo->rollBack();
            echo json_encode(['msg' => 'Program not found']);
            exit;
        }

        $program_name = $program['program_name'];

        // Delete the program record from the database
        $sql_delete_program = "DELETE FROM tbl_programs WHERE program_id = :program_id";
        $stmt_delete_program = $pdo->prepare($sql_delete_program);
        $stmt_delete_program->bindParam(':program_id', $program_id, PDO::PARAM_INT);

        // Execute the SQL statement to delete the program
        if ($stmt_delete_program->execute()) {
            $action_id = ACTION_DELETE_PROGRAM;

            $sql_log = 'INSERT INTO tbl_actionlogs (user_id, action_id, action_desc) VALUES (:user_id, :action_id, :action_desc)';
            $stmt_log = $pdo->prepare($sql_log);
            $user_id = $_SESSION['admin_id']; 
            $action_desc = 'Deleted program ' . $program_name;
            $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT); // Using variable
            $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
            $stmt_log->execute();

            $pdo->commit();
            echo json_encode(['success' => 'Program deleted successfully']);
            exit;

        } else {
            // Rollback the transaction if deletion of the program fails
            $pdo->rollBack();
            echo json_encode(['msg' => 'Failed to delete program']);
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