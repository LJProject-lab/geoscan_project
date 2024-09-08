<?php
// Include database connection
require_once '../../config.php';
require_once 'action-ids.php';

// Process delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve company_id from POST data
    $company_id = isset($_POST['company_id']) ? $_POST['company_id'] : null;

    if (!$company_id) {
        $msg = 'Company ID is missing.';
        echo json_encode(['msg' => $msg]);
        exit;
    }

    try {
        // Start a database transaction
        $pdo->beginTransaction();

        // Fetch name 
        $sql_fetch = "SELECT company_name FROM tbl_companies WHERE company_id = :company_id";
        $stmt_fetch = $pdo->prepare($sql_fetch);
        $stmt_fetch->bindParam(':company_id', $company_id, PDO::PARAM_STR);
        $stmt_fetch->execute();

        $company = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
        if (!$company) {
            // If the company doesn't exist, rollback and exit
            $pdo->rollBack();
            echo json_encode(['msg' => 'Company not found']);
            exit;
        }

        $company_name = $company['company_name'];

        // Delete the company record from the database
        $sql_delete_company = "DELETE FROM tbl_companies WHERE company_id = :company_id";
        $stmt_delete_company = $pdo->prepare($sql_delete_company);
        $stmt_delete_company->bindParam(':company_id', $company_id, PDO::PARAM_STR);

        // Execute the SQL statement to delete the company
        if ($stmt_delete_company->execute()) {
            // Log the deletion action
            $action_id = ACTION_DELETE_COMPANY;
            $sql_log = 'INSERT INTO tbl_actionlogs (user_id, action_id, action_desc, created_at) VALUES (:user_id, :action_id, :action_desc, NOW())';
            $stmt_log = $pdo->prepare($sql_log);
            $user_id = $_SESSION['admin_id']; // Replace with actual user ID
            $action_desc = 'Deleted company ' . $company_name;
            $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
            $stmt_log->execute();

            // Commit the transaction
            $pdo->commit();
            echo json_encode(['success' => 'Company deleted successfully']);
            exit;

        } else {
            // Rollback the transaction if deletion of the company fails
            $pdo->rollBack();
            echo json_encode(['msg' => 'Failed to delete company']);
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
