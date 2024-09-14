<?php
require_once '../config.php';
require_once '../action-ids.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if all necessary fields are set
    if (isset($_POST['adjustment_id'])) {
        $adjustment_id = $_POST['adjustment_id'];
        $status = 'Approved';  // Set the status to 'Approved'

        // Update query to change the status
        $stmt = $pdo->prepare("UPDATE tbl_adjustments SET status = ? WHERE id = ?");
        $result = $stmt->execute([$status, $adjustment_id]);

        if ($result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = 'Adjustment request has been sent successfully.';

            // Fetch adjustment details to log
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
                $reason = $adjustment['reason']; // Assuming you want to include the reason in the log
                $form_type = $adjustment['form_type']; // Adjust if needed

                // Insert into tbl_actionlogs
                $sql_log = 'INSERT INTO tbl_actionlogs (user_id, student_id, action_id, action_desc) VALUES (:user_id, :student_id, :action_id, :action_desc)';
                $stmt_log = $pdo->prepare($sql_log);
                $user_id = $_SESSION['coordinator_id'];
                
                $action_desc = "Adjustment request for Student " . $firstname . ' ' . $lastname . " has been approved. Reason: " . $reason;
                $action_id = ACTION_SET_STATUS_ADJUSTMENT_STUDENT; // Define this action ID in action-ids.php if not already defined

                $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt_log->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT);
                $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
                $stmt_log->execute();
            }
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = 'Failed to update adjustment request.';
        }
    } else {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Missing necessary data.';
    }
}

exit();
?>
