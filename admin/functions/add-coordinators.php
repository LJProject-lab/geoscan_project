<?php

// Include database connection
require_once '../../config.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve form data
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate catering name
    if (empty(trim($username))) {
        $msg = 'Please enter a Catering Name.';
        // Return the error message
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
            // Return the error message
            echo json_encode(['msg' => $msg]);
            exit();
        }
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

    // Generate a unique Coordinator ID
    $coordinator_id = mt_rand(10000, 99999);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the first SQL statement
    $sql = 'INSERT INTO tbl_coordinators (coordinator_id, username, firstname, lastname, email,  password) VALUES (:coordinator_id, :username, :firstname, :lastname, :email, :password)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':coordinator_id', $coordinator_id, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    
    if ($stmt->execute()) {       
        // Return success message
        $success = 'Coordinator account successfully created.';
        echo json_encode(['success' => $success]);
        exit();
    } else {
        // Return error message if something goes wrong
        $msg = 'Oops! Something went wrong. Please try again later.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Close statement and connection
    unset($stmt);
    unset($stmt2);
    unset($DB_con);
}
?>
