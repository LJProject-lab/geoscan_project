<?php

include "config.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $student_id = $_POST['student_id'];

    // Check if the student_id is already registered
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE student_id = :student_id");
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $_SESSION['alert_type'] = "error";
        $_SESSION['alert_message'] = "This student_id already exists.";
    } else {
        $program_id = $_POST['program_id'];
        
        // Extract the last 4 digits of the student_id to use as the PIN
        $pin = substr($student_id, -4);
        
        // Hash the PIN before storing it in the database
        $hashedPin = password_hash($pin, PASSWORD_DEFAULT);
        
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $coordinator_id = $_POST['coordinator_id'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("INSERT INTO tbl_users (student_id, pin, firstname, lastname, email, phone, address, program_id, coordinator_id, status) 
        VALUES (:student_id, :pin, :firstname, :lastname, :email, :phone, :address, :program_id, :coordinator_id, :status)");
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':program_id', $program_id);
        $stmt->bindParam(':pin', $hashedPin);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':coordinator_id', $coordinator_id);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            $_SESSION['alert_type'] = "success";
            $_SESSION['alert_message'] = "Intern added successfully.";
        } else {
            $_SESSION['alert_type'] = "error";
            $_SESSION['alert_message'] = "Failed to add intern.";
        }
    }

    header("Location: register_intern.php");
    exit;
}
?>
