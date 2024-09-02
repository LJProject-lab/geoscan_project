<?php

// Include database connection and action IDs
require_once '../../config.php';
require_once 'action-ids.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $coordinator_id = isset($_POST['coordinator_id']) ? $_POST['coordinator_id'] : null;

    if (!$coordinator_id) {
        $msg = 'Coordinator ID is missing.';
        echo json_encode(['msg' => $msg]);
        exit;
    }

    try {
        $pdo->beginTransaction();


        // Fetch name 
        $sql_fetch = "SELECT username FROM tbl_coordinators WHERE coordinator_id = :coordinator_id";
        $stmt_fetch = $pdo->prepare($sql_fetch);
        $stmt_fetch->bindParam(':coordinator_id', $coordinator_id, PDO::PARAM_STR);
        $stmt_fetch->execute();

        $coordinator = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
        if (!$coordinator) {
            // If the coordinator doesn't exist, rollback and exit
            $pdo->rollBack();
            echo json_encode(['msg' => 'Coordinator not found']);
            exit;
        }

        $username = $coordinator['username'];


        $sql_delete_coordinator = "DELETE FROM tbl_coordinators WHERE coordinator_id = :coordinator_id";
        $stmt_delete_coordinator = $pdo->prepare($sql_delete_coordinator);
        $stmt_delete_coordinator->bindParam(':coordinator_id', $coordinator_id, PDO::PARAM_INT);

        if ($stmt_delete_coordinator->execute()) {
            $action_id = ACTION_DELETE_COORDINATOR;

            $sql_log = 'INSERT INTO tbl_actionlogs (user_id, action_id, action_desc) VALUES (:user_id, :action_id, :action_desc)';
            $stmt_log = $pdo->prepare($sql_log);
            $user_id = $_SESSION['admin_id']; 
            $action_desc = 'Deleted coordinator ' . $username;
            $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT); // Using variable
            $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
            $stmt_log->execute();

            $pdo->commit();
            echo json_encode(['success' => 'Coordinator deleted successfully']);
            exit;

        } else {
            $pdo->rollBack();
            echo json_encode(['msg' => 'Failed to delete coordinator']);
            exit;
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['msg' => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['msg' => 'Invalid request method']);
    exit;
}
?>
