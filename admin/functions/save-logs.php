<?php
require '../../config.php';
require_once 'action-ids.php';

header('Content-Type: application/json');

if (isset($_POST['student_id']) && isset($_POST['adjustments'])) {
    $studentId = $_POST['student_id'];
    $adjustments = $_POST['adjustments'];
    $adjustment_id = isset($_POST['adjustment_id']) ? $_POST['adjustment_id'] : null;

    try {
        // Prepare the query to insert new time-out records
        $query = "INSERT INTO tbl_timelogs (student_id, type, timestamp, longitude, latitude, photo) 
                  VALUES (:student_id, 'time_out', :timestamp, :longitude, :latitude, '')";
        $stmt = $pdo->prepare($query);

        // Begin a transaction
        $pdo->beginTransaction();

        foreach ($adjustments as $date => $details) {
            $timestamp = $date . ' ' . $details['time'];
            $longitude = $details['longitude'];
            $latitude = $details['latitude'];

            // Debugging information
            if (!$stmt->execute([
                ':student_id' => $studentId,
                ':timestamp' => $timestamp,
                ':longitude' => $longitude,
                ':latitude' => $latitude
            ])) {
                $error = $stmt->errorInfo();
                throw new Exception("Failed to insert time-out record: " . $error[2]);
            }
        }

        // Update adjustment status to 'Adjusted'
        if ($adjustment_id) {
            $status = 'Adjusted';

            $stmt_update = $pdo->prepare("UPDATE tbl_adjustments SET status = ? WHERE id = ?");
            if (!$stmt_update->execute([$status, $adjustment_id])) {
                $error = $stmt_update->errorInfo();
                throw new Exception("Failed to update adjustment status: " . $error[2]);
            }

            // Fetch adjustment details to log
            $stmt_fetch = $pdo->prepare("
                SELECT a.*, u.firstname, u.lastname
                FROM tbl_adjustments a
                LEFT JOIN tbl_users u ON a.student_id = u.student_id
                WHERE a.id = :adjustment_id
            ");
            $stmt_fetch->bindParam(':adjustment_id', $adjustment_id, PDO::PARAM_INT);
            if (!$stmt_fetch->execute()) {
                $error = $stmt_fetch->errorInfo();
                throw new Exception("Failed to fetch adjustment details: " . $error[2]);
            }
            $adjustment = $stmt_fetch->fetch(PDO::FETCH_ASSOC);

            if ($adjustment) {
                $student_id = $adjustment['student_id'];
                $firstname = $adjustment['firstname'];
                $lastname = $adjustment['lastname'];
                $reason = $adjustment['reason'];
                // Insert into tbl_actionlogs
                $sql_log = 'INSERT INTO tbl_actionlogs (user_id, student_id, action_id, action_desc) VALUES (:user_id, :student_id, :action_id, :action_desc)';
                $stmt_log = $pdo->prepare($sql_log);
                $user_id = $_SESSION['admin_id'];

                $action_desc = "Adjustment request for Student " . $firstname . ' ' . $lastname . " has been approved. Reason: " . $reason;
                $action_id = ACTION_SET_STATUS_ADJUSTMENT_STUDENT; // Define this action ID in action-ids.php if not already defined

                if (!$stmt_log->execute([
                    ':user_id' => $user_id,
                    ':student_id' => $student_id,
                    ':action_id' => $action_id,
                    ':action_desc' => $action_desc
                ])) {
                    $error = $stmt_log->errorInfo();
                    throw new Exception("Failed to insert action log: " . $error[2]);
                }
            }
        }

        // Commit the transaction
        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Time Log Adjusted Successfully']);
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Required data not provided']);
}
?>
