<?php

// Include database connection
require_once '../../config.php';
require_once 'action-ids.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve form data
    $department_name = $_POST['department_name'];
    $department_code = $_POST['department_code'];

    // Validate program name
    if (empty(trim($department_name))) {
        $msg = 'Please enter a Department Name.';
        // Return the error message
        echo json_encode(['msg' => $msg]);
        exit();
    } else {
        // Check if program name already exists
        $sql_check_department = 'SELECT department_name FROM tbl_departments WHERE department_name = :department_name';
        $stmt_check_department = $pdo->prepare($sql_check_department);
        $stmt_check_department->bindParam(':department_name', $department_name, PDO::PARAM_STR);
        $stmt_check_department->execute();
        if ($stmt_check_department->rowCount() > 0) {
            $msg = 'Department Name is already taken.';
            // Return the error message
            echo json_encode(['msg' => $msg]);
            exit();
        }
    }

    if (empty(trim($department_name))) {
        $msg = 'Please enter a Department Name.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    if (empty(trim($department_code))) {
        $msg = 'Please enter a Department code.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Generate a unique program ID
    $department_id = mt_rand(10000, 99999);

    // Prepare and execute the first SQL statement
    $sql = 'INSERT INTO tbl_departments (department_id, department_name, department_code) VALUES (:department_id, :department_name, :department_code)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':department_id', $department_id, PDO::PARAM_STR);
    $stmt->bindParam(':department_name', $department_name, PDO::PARAM_STR);
    $stmt->bindParam(':department_code', $department_code, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $sql_log = 'INSERT INTO tbl_actionlogs (user_id, action_id, action_desc) VALUES (:user_id, :action_id, :action_desc)';
        $stmt_log = $pdo->prepare($sql_log);
        $user_id = $_SESSION['admin_id'];
        $action_desc = 'Department ' . $department_name . ' created';
        $action_id = ACTION_CREATE_DEPARTMENT;
        $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT);
        $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
        $stmt_log->execute();

        // Return success message
        $success = 'Department successfully created.';
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