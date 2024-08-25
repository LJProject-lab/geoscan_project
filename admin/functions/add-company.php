<?php

// Include database connection
require_once '../../config.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve form data
    $company_name = $_POST['company_name'];

    // Validate company name
    if (empty(trim($company_name))) {
        $msg = 'Please enter a Company Name.';
        // Return the error message
        echo json_encode(['msg' => $msg]);
        exit();
    } else {
        // Check if company name already exists
        $sql_check_company = 'SELECT company_name FROM tbl_companies WHERE company_name = :company_name';
        $stmt_check_company = $pdo->prepare($sql_check_company);
        $stmt_check_company->bindParam(':company_name', $company_name, PDO::PARAM_STR);
        $stmt_check_company->execute();
        if ($stmt_check_company->rowCount() > 0) {
            $msg = 'Company Name is already taken.';
            // Return the error message
            echo json_encode(['msg' => $msg]);
            exit();
        }
    }

    if (empty(trim($company_name))) {
        $msg = 'Please enter a company Name.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Generate a unique company ID
    $company_id = mt_rand(10000, 99999);

    // Prepare and execute the first SQL statement
    $sql = 'INSERT INTO tbl_companies (company_id, company_name) VALUES (:company_id, :company_name)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':company_id', $company_id, PDO::PARAM_STR);
    $stmt->bindParam(':company_name', $company_name, PDO::PARAM_STR);
    
    if ($stmt->execute()) {       
        // Return success message
        $success = 'Company successfully created.';
        echo json_encode(['success' => $success]);
        exit();
    } else {
        // Return error message if something goes wrong
        $msg = 'Oops! Something went wrong. Please try again later.';
        echo json_encode(['msg' => $msg]);
        exit();
    }


}
?>
