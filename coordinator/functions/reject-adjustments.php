<?php
require_once '../config.php';
require_once '../action-ids.php'; 

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure necessary fields are set
    if (isset($_POST['adjustment_id']) && isset($_POST['reject_reason'])) {
        $adjustment_id = $_POST['adjustment_id'];
        $reject_reason = $_POST['reject_reason'];
        $status = 'Rejected'; // Set the status to 'Rejected'

        // Update query to set status to 'Rejected' and store the rejection reason
        $stmt = $pdo->prepare("UPDATE tbl_adjustments SET status = ?, reject_reason = ? WHERE id = ?");
        $result = $stmt->execute([$status, $reject_reason, $adjustment_id]);

        if ($result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = 'Adjustment request has been rejected.';

            // Fetch the adjustment details
            $stmt_fetch = $pdo->prepare("
                SELECT a.*, u.firstname, u.lastname
                FROM tbl_adjustments a
                LEFT JOIN tbl_users u ON a.student_id = u.student_id
                WHERE a.id = :adjustment_id
            ");
            $stmt_fetch->bindParam(':adjustment_id', $adjustment_id, PDO::PARAM_INT);
            $stmt_fetch->execute();
            $adjustment = $stmt_fetch->fetch(PDO::FETCH_ASSOC);

            if ($adjustment) {
                $student_id = $adjustment['student_id'];
                $firstname = $adjustment['firstname'];
                $lastname = $adjustment['lastname'];
                $reason = $adjustment['reason'];

                // Log action
                $sql_log = 'INSERT INTO tbl_actionlogs (user_id, student_id, action_id, action_desc) VALUES (:user_id, :student_id, :action_id, :action_desc)';
                $stmt_log = $pdo->prepare($sql_log);
                $user_id = $_SESSION['coordinator_id'];
                $action_desc = "Adjustment request for Student " . $firstname . ' ' . $lastname . " has been rejected. Reason for rejection: " . $reject_reason;
                $action_id = ACTION_SET_STATUS_ADJUSTMENT_STUDENT; // Ensure this constant is defined in action-ids.php

                // Debugging: Log or output the values
                var_dump($user_id, $student_id, $action_id, $action_desc);

                // Bind the parameters and execute
                $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt_log->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT);
                $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
                $log_result = $stmt_log->execute();

                if (!$log_result) {
                    // Capture any error from the query
                    $errorInfo = $stmt_log->errorInfo();
                    var_dump($errorInfo);
                    $_SESSION['alert_type'] = 'error';
                    $_SESSION['alert_message'] = 'Failed to log action. Error: ' . $errorInfo[2];
                }
            } else {
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_message'] = 'Failed to fetch adjustment details.';
            }
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = 'Failed to reject adjustment request.';
        }
    } else {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Missing necessary data.';
    }
}

exit();
?>
