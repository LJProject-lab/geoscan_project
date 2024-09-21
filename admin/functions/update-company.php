<?php
// Include database connection
require_once '../../config.php';
require_once 'action-ids.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $companyId = isset($_POST['company_id']) ? $_POST['company_id'] : null;
    $companyName = isset($_POST['company_name']) ? $_POST['company_name'] : null;





    // Validate company ID
    if (!$companyId) {
        $msg = 'Company ID is required.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Validate company name
    if (empty(trim($companyName))) {
        $msg = 'Please enter a company name.';
        echo json_encode(['msg' => $msg]);
        exit();
    }


    // Check if company name already exists for the specified company
    $sql_check_company_name = 'SELECT COUNT(*) AS num_companies FROM tbl_companies WHERE company_id != :company_id AND company_name = :company_name';
    $stmt_check_company_name = $pdo->prepare($sql_check_company_name);
    $stmt_check_company_name->bindParam(':company_id', $companyId, PDO::PARAM_INT);
    $stmt_check_company_name->bindParam(':company_name', $companyName, PDO::PARAM_STR);
    $stmt_check_company_name->execute();
    $row = $stmt_check_company_name->fetch(PDO::FETCH_ASSOC);

    if ($row['num_companies'] > 0) {
        $msg = 'A company with the same name already exists for this company.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Prepare SQL statement to update company
    $sql = 'UPDATE tbl_companies SET company_name = :company_name WHERE company_id = :company_id';
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);
    $stmt->bindParam(':company_name', $companyName, PDO::PARAM_STR);



    try {
        // Execute the prepared statement
        if ($stmt->execute()) {


            $sql_log = 'INSERT INTO tbl_actionlogs (user_id, action_id, action_desc) VALUES (:user_id, :action_id, :action_desc)';
            $stmt_log = $pdo->prepare($sql_log);
            $user_id = $_SESSION['admin_id'];
            $action_desc = 'Company ' . $companyName . ' updated';
            $action_id = ACTION_UPDATE_COMPANY;
            $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
            $stmt_log->execute();



            $success = 'Company successfully updated!';
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