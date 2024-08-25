<?php
// Include database connection
require_once '../../config.php';

// Process delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve company_id from POST data
    $company_id = isset($_POST['company_id']) ? $_POST['company_id'] : null;

    if (!$company_id) {
        $msg = 'Course ID is missing.';
        echo json_encode(['msg' => $msg]);
        exit;
    }

    try {
        // Start a database transaction
        $pdo->beginTransaction();


        // Delete the company record from the database
        $sql_delete_company = "DELETE FROM tbl_companies WHERE company_id = :company_id";
        $stmt_delete_company = $pdo->prepare($sql_delete_company);
        $stmt_delete_company->bindParam(':company_id', $company_id, PDO::PARAM_INT);

        // Execute the SQL statement to delete the company
        if ($stmt_delete_company->execute()) {
            
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