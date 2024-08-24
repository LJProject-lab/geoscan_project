<?php
// Include database connection
require_once '../../config.php';

// Process delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve coordinator_id from POST data
    $coordinator_id = isset($_POST['coordinator_id']) ? $_POST['coordinator_id'] : null;

    if (!$coordinator_id) {
        $msg = 'Coordinator ID is missing.';
        echo json_encode(['msg' => $msg]);
        exit;
    }

    try {
        // Start a database transaction
        $pdo->beginTransaction();


        // Delete the coordinator record from the database
        $sql_delete_coordinator = "DELETE FROM tbl_coordinators WHERE coordinator_id = :coordinator_id";
        $stmt_delete_coordinator = $pdo->prepare($sql_delete_coordinator);
        $stmt_delete_coordinator->bindParam(':coordinator_id', $coordinator_id, PDO::PARAM_INT);

        // Execute the SQL statement to delete the coordinator
        if ($stmt_delete_coordinator->execute()) {
            
            $pdo->commit();
            echo json_encode(['success' => 'Coordinator deleted successfully']);
            exit;

        } else {
            // Rollback the transaction if deletion of the coordinator fails
            $pdo->rollBack();
            echo json_encode(['msg' => 'Failed to delete coordinator']);
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