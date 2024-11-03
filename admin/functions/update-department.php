<?php
// Include database connection
require_once '../../config.php';
require_once 'action-ids.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $departmentId = isset($_POST['department_id']) ? $_POST['department_id'] : null;
    $departmentName = isset($_POST['department_name']) ? $_POST['department_name'] : null;
    $departmentCode = isset($_POST['department_code']) ? $_POST['department_code'] : null;





    // Validate program ID
    if (!$departmentId) {
        $msg = 'program ID is required.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Validate program name
    if (empty(trim($departmentName))) {
        $msg = 'Please enter a program name.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Validate program description
    if (empty(trim($departmentCode))) {
        $msg = 'Please enter a program description.';
        echo json_encode(['msg' => $msg]);
        exit();
    }
    // Check if program name already exists for the specified program
    $sql_check_department_name = 'SELECT COUNT(*) AS num_departments FROM tbl_departments WHERE department_id != :department_id AND department_name = :department_name';
    $stmt_check_department_name = $pdo->prepare($sql_check_department_name);
    $stmt_check_department_name->bindParam(':department_id', $departmentId, PDO::PARAM_INT);
    $stmt_check_department_name->bindParam(':department_name', $departmentName, PDO::PARAM_STR);
    $stmt_check_department_name->execute();
    $row = $stmt_check_department_name->fetch(PDO::FETCH_ASSOC);

    if ($row['num_departments'] > 0) {
        $msg = 'A Department with the same name already exists for this program.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Prepare SQL statement to update program
    $sql = 'UPDATE tbl_departments SET department_name = :department_name, department_code = :department_code WHERE department_id = :department_id';
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT);
    $stmt->bindParam(':department_name', $departmentName, PDO::PARAM_STR);
    $stmt->bindParam(':department_code', $departmentCode, PDO::PARAM_STR);



    try {
        // Execute the prepared statement
        if ($stmt->execute()) {


            $sql_log = 'INSERT INTO tbl_actionlogs (user_id, action_id, action_desc) VALUES (:user_id, :action_id, :action_desc)';
            $stmt_log = $pdo->prepare($sql_log);
            $user_id = $_SESSION['admin_id'];
            $action_desc = 'Department ' . $departmentName . ' updated';
            $action_id = ACTION_UPDATE_PROGRAM;
            $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT);
            $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
            $stmt_log->execute();






            $success = 'DEpartment successfully updated!';
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