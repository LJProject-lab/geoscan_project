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
        session_start();
        $_SESSION['error'] = "This student_id already exists.";
    } else {
        $course = $_POST['course'];
        
        // Extract the last 4 digits of the student_id to use as the PIN
        $pin = substr($student_id, -4);
        
        // Hash the PIN before storing it in the database
        $hashedPin = password_hash($pin, PASSWORD_DEFAULT);
        
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $coordinator = $_POST['coordinator'];

        $stmt = $pdo->prepare("INSERT INTO tbl_users (student_id, course, pin, firstname, lastname, email, phone, address, coordinator) 
        VALUES (:student_id, :course, :pin, :firstname, :lastname, :email, :phone, :address, :coordinator)");
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':course', $course);
        $stmt->bindParam(':pin', $hashedPin);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':coordinator', $coordinator);

        if ($stmt->execute()) {
            session_start();
            $_SESSION['success'] = "Intern added successfully.";
        } else {
            echo 'Failed';
        }
    }
}

header("Location: register_intern.php");
exit;
?>
