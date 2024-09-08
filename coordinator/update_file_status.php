<?php
require 'config.php'; // Include your database connection file
require_once 'action-ids.php';
include "crypt_helper.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['file_id'])) {
        $student_id = decryptData($_POST['student_id']);
        $file_id = $_POST['file_id'];
        $status = '';

        // Fetch name 
        $sql_fetch = "SELECT A.firstname, A.lastname, B.form_type, A.student_id  FROM tbl_users AS A LEFT JOIN tbl_requirements AS B ON A.student_id = B.student_id WHERE A.student_id = :student_id AND B.id = :file_id";
        $stmt_fetch = $pdo->prepare($sql_fetch);
        $stmt_fetch->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $stmt_fetch->bindParam(':file_id', $file_id, PDO::PARAM_STR);
        $stmt_fetch->execute();

        $student = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
        if (!$student) {
            // If the student doesn't exist, rollback and exit
            $pdo->rollBack();
            echo json_encode(['msg' => 'Student not found']);
            exit;
        }

        // Determine which button was clicked
        if (isset($_POST['approve'])) {
            $status = 'Approved';
        } elseif (isset($_POST['cancel'])) {
            $status = 'Cancelled';
        }

        // Update the file status in the database
        if ($status) {
            $stmt = $pdo->prepare("UPDATE tbl_requirements SET status = :status WHERE id = :file_id");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':file_id', $file_id);


            if ($stmt->execute()) {
                $student_id = $student['student_id'];
                $firstname = $student['firstname'];
                $lastname = $student['lastname'];
                $form_type = $student['form_type'];

                $sql_log = 'INSERT INTO tbl_actionlogs (user_id, student_id, action_id, action_desc) VALUES (:user_id, :student_id, :action_id, :action_desc)';
                $stmt_log = $pdo->prepare($sql_log);
                $user_id = $_SESSION['coordinator_id'];

                $action_desc = "Requirement " . $form_type . " Set " . ($status == "Approved" ? "Approved" : "Cancelled") . " for Student " . $firstname . ' ' . $lastname;
                ;

                $action_id = ACTION_SET_STATUS_REQUIREMENT_STUDENT;
                $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt_log->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT);
                $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
                $stmt_log->execute();
            }
        }
    }
}

// Redirect back to the previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>