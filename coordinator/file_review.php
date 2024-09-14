<?php

require 'config.php'; // Include your database connection file
require_once 'action-ids.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['file_id'])) {
        $student_id = $_POST['student_id'];
        $file_id = $_POST['file_id'];
        $status = '';
        $cancel_reason = '';

        // Start a transaction to ensure data consistency
        $pdo->beginTransaction();

        try {
            // Fetch student and requirement information
            $sql_fetch = "SELECT A.firstname, A.lastname, B.form_type, A.student_id  
                          FROM tbl_users AS A 
                          LEFT JOIN tbl_requirements AS B ON A.student_id = B.student_id 
                          WHERE A.student_id = :student_id AND B.id = :file_id";
            $stmt_fetch = $pdo->prepare($sql_fetch);
            $stmt_fetch->bindParam(':student_id', $student_id, PDO::PARAM_STR);
            $stmt_fetch->bindParam(':file_id', $file_id, PDO::PARAM_STR);
            $stmt_fetch->execute();

            $student = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
            if (!$student) {
                // If the student doesn't exist, rollback and exit
                $pdo->rollBack();
                header('Location: ' . $_SERVER['HTTP_REFERER'] . '?status=error');
                exit;
            }

            // Determine which button was clicked
            if (isset($_POST['approve'])) {
                $status = 'Approved';
            } elseif (isset($_POST['cancel'])) {
                $status = 'Cancelled';
                $cancel_reason = isset($_POST['cancel_reason']) ? $_POST['cancel_reason'] : '';
            }

            // Update the file status in the database
            if ($status) {
                if ($status === 'Cancelled') {
                    // Update status and reason for cancellation
                    $stmt = $pdo->prepare("UPDATE tbl_requirements SET status = :status, cancel_reason = :cancel_reason WHERE id = :file_id");
                    $stmt->bindParam(':cancel_reason', $cancel_reason);
                } else {
                    // Update only the status
                    $stmt = $pdo->prepare("UPDATE tbl_requirements SET status = :status WHERE id = :file_id");
                }
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':file_id', $file_id);
                $stmt->execute();

                // Log the action
                $student_id = $student['student_id'];
                $firstname = $student['firstname'];
                $lastname = $student['lastname'];
                $form_type = $student['form_type'];

                $sql_log = 'INSERT INTO tbl_actionlogs (user_id, student_id, action_id, action_desc) VALUES (:user_id, :student_id, :action_id, :action_desc)';
                $stmt_log = $pdo->prepare($sql_log);
                $user_id = $_SESSION['coordinator_id'];

                $action_desc = "Requirement " . $form_type . " Set " . ($status == "Approved" ? "Approved" : "Cancelled") . " for Student " . $firstname . ' ' . $lastname;
                if ($status === 'Cancelled') {
                    $action_desc .= " with reason: " . $cancel_reason;
                }

                $action_id = ACTION_SET_STATUS_REQUIREMENT_STUDENT;
                $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt_log->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT);
                $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
                $stmt_log->execute();
            }

            // Commit the transaction
            $pdo->commit();

            // Redirect with the correct status
            $redirectUrl = $_SERVER['HTTP_REFERER'] . '?status=' . $status;
            header('Location: ' . $redirectUrl);
            exit();

        } catch (Exception $e) {
            // Rollback the transaction in case of error
            $pdo->rollBack();
            // Handle the error (e.g., log it, show an error message)
            error_log($e->getMessage());
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?status=error');
            exit;
        }
    }
}
?>
