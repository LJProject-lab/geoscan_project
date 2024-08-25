<?php
// Include database connection
require_once '../../config.php';

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


        // Delete the user record from the database
        $sql_delete_user = "DELETE FROM tbl_users WHERE student_id = :student_id";
        $stmt_delete_user = $pdo->prepare($sql_delete_user);
        $stmt_delete_user->bindParam(':student_id', $student_id, PDO::PARAM_INT);

        // Execute the SQL statement to delete the user
        if ($stmt_delete_user->execute()) {


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