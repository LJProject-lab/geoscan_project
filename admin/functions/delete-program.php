<?php
// Include database connection
require_once '../../config.php';

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


        // Delete the program record from the database
        $sql_delete_program = "DELETE FROM tbl_programs WHERE program_id = :program_id";
        $stmt_delete_program = $pdo->prepare($sql_delete_program);
        $stmt_delete_program->bindParam(':program_id', $program_id, PDO::PARAM_INT);

        // Execute the SQL statement to delete the program
        if ($stmt_delete_program->execute()) {
            
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