<?php

require_once '../../config.php';
require_once 'action-ids.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_id = $_POST['department_id'];
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate catering name
    if (empty(trim($username))) {
        $msg = 'Please enter a Catering Name.';
        echo json_encode(['msg' => $msg]);
        exit();
    } else {
        // Check if catering name already exists
        $sql_check_coordinator = 'SELECT username FROM tbl_coordinators WHERE username = :username';
        $stmt_check_coordinator = $pdo->prepare($sql_check_coordinator);
        $stmt_check_coordinator->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt_check_coordinator->execute();
        if ($stmt_check_coordinator->rowCount() > 0) {
            $msg = 'Username Name is already taken.';
            echo json_encode(['msg' => $msg]);
            exit();
        }
    }

    if (empty(trim($department_id))) {
        $msg = 'Please select Department.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    if (empty(trim($firstname))) {
        $msg = 'Please enter a First Name.';
        echo json_encode(['msg' => $msg]);
        exit();
    }
    if (empty(trim($lastname))) {
        $msg = 'Please enter a Last Name.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Validate email
    if (empty(trim($email))) {
        $msg = 'Please enter a Coordinator email.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Validate password
    if (empty(trim($password))) {
        $msg = 'Please enter a password.';
        echo json_encode(['msg' => $msg]);
        exit();
    } elseif (strlen($password) < 8) {
        $msg = 'Password must be at least 8 characters long.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    $coordinator_id = mt_rand(10000, 99999);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = 'INSERT INTO tbl_coordinators (coordinator_id, username, firstname, lastname, email, department_id, password) VALUES (:coordinator_id, :username, :firstname, :lastname, :email, :department_id, :password)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':coordinator_id', $coordinator_id, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':department_id', $department_id, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $sql_log = 'INSERT INTO tbl_actionlogs (user_id, action_id, action_desc) VALUES (:user_id, :action_id, :action_desc)';
        $stmt_log = $pdo->prepare($sql_log);
        $user_id = $_SESSION['admin_id']; 
        $action_desc = 'Coordinator account created for ' . $username;
        $action_id = ACTION_CREATE_COORDINATOR;
        $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT); 
        $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
        $stmt_log->execute();

        $success = 'Coordinator account successfully created.';
        echo json_encode(['success' => $success]);
        exit();
    } else {
        $msg = 'Oops! Something went wrong. Please try again later.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    unset($stmt);
    unset($stmt_log);
    unset($pdo);
}
?>