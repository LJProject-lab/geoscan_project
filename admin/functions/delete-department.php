<?php
// Include database connection
require_once '../../config.php';
require_once 'action-ids.php';

// Process delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve program_id from POST data
    $department_id = isset($_POST['department_id']) ? $_POST['department_id'] : null;

    if (!$department_id) {
        $msg = 'Department ID is missing.';
        echo json_encode(['msg' => $msg]);
        exit;
    }

    try {
        // Start a database transaction
        $pdo->beginTransaction();

        // Fetch name 
        $sql_fetch = "SELECT department_name FROM tbl_departments WHERE department_id = :department_id";
        $stmt_fetch = $pdo->prepare($sql_fetch);
        $stmt_fetch->bindParam(':department_id', $department_id, PDO::PARAM_STR);
        $stmt_fetch->execute();

        $department = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
        if (!$department) {
            // If the program doesn't exist, rollback and exit
            $pdo->rollBack();
            echo json_encode(['msg' => 'Department not found']);
            exit;
        }

        $department_name = $department['department_name'];

        // Delete the program record from the database
        $sql_delete_department = "DELETE FROM tbl_departments WHERE department_id = :department_id";
        $stmt_delete_department = $pdo->prepare($sql_delete_department);
        $stmt_delete_department->bindParam(':department_id', $department_id, PDO::PARAM_INT);

        // Execute the SQL statement to delete the program
        if ($stmt_delete_department->execute()) {
            $action_id = ACTION_DELETE_DEPARTMENT;

            $sql_log = 'INSERT INTO tbl_actionlogs (user_id, action_id, action_desc) VALUES (:user_id, :action_id, :action_desc)';
            $stmt_log = $pdo->prepare($sql_log);
            $user_id = $_SESSION['admin_id']; 
            $action_desc = 'Deleted DEPARTMENT ' . $department_name;
            $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT); // Using variable
            $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
            $stmt_log->execute();

            $pdo->commit();
            echo json_encode(['success' => 'Department deleted successfully']);
            exit;

        } else {
            // Rollback the transaction if deletion of the program fails
            $pdo->rollBack();
            echo json_encode(['msg' => 'Failed to delete department']);
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