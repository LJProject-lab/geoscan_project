<?php
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$credential = $data['credential'] ?? null;
$longitude = $data['longitude'] ?? null;
$latitude = $data['latitude'] ?? null;
$timeSelection = $data['timeSelection'] ?? null;

if (!$credential || !$longitude || !$latitude || !$timeSelection) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit;
}

$credential_id = $credential['id'] ?? null;
$authenticator_data = $credential['response']['authenticatorData'] ?? null;
$client_data_json = $credential['response']['clientDataJSON'] ?? null;
$signature = $credential['response']['signature'] ?? null;

if (!$credential_id || !$authenticator_data || !$client_data_json || !$signature) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Incomplete assertion data.']);
    exit;
}

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $sql = "SELECT * FROM tbl_users WHERE credential_id = :credential_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':credential_id' => $credential_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Map the timeSelection to formal messages
        $timeSelectionMessages = [
            'time_in' => 'Time In',
            'time_out' => 'Time Out'
        ];

        // Check if the user already has a time in record
        if ($timeSelection === 'time_out') {
            $check_time_in_sql = "SELECT * FROM tbl_timelogs WHERE student_id = :student_id AND DATE(timestamp) = CURDATE() AND type = 'time_in'";
            $check_time_in_stmt = $pdo->prepare($check_time_in_sql);
            $check_time_in_stmt->execute([':student_id' => $user['student_id']]);
            $time_in_log = $check_time_in_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$time_in_log) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'You must time in before you can time out.']);
                exit;
            }
        }

        // Check if a log entry for the selected type already exists for today
        $check_sql = "SELECT * FROM tbl_timelogs WHERE student_id = :student_id AND DATE(timestamp) = CURDATE() AND type = :type";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([':student_id' => $user['student_id'], ':type' => $timeSelection]);
        $existing_log = $check_stmt->fetch(PDO::FETCH_ASSOC);

        $formalMessage = $timeSelectionMessages[$timeSelection] ?? 'Unknown';

        if ($existing_log) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => "You have already recorded your $formalMessage for today."]);
        } else {
            $pin = ''; // If needed
            $photo = ''; // Set to an empty string or binary data if applicable

            $log_sql = "INSERT INTO tbl_timelogs (student_id, pin, type, timestamp, longitude, latitude, photo) 
                        VALUES (:student_id, :pin, :type, NOW(), :longitude, :latitude, :photo)";
            $log_stmt = $pdo->prepare($log_sql);
            
            try {
                $log_stmt->execute([
                    ':student_id' => $user['student_id'],
                    ':pin' => $pin,
                    ':type' => $timeSelection,
                    ':longitude' => $longitude,
                    ':latitude' => $latitude,
                    ':photo' => $photo
                ]);

                if ($log_stmt->rowCount() > 0) {
                    error_log("Log inserted successfully");
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true]);
                } else {
                    error_log("Log insert failed");
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Failed to insert log.']);
                }
            } catch (Exception $e) {
                error_log("Insertion error: " . $e->getMessage());
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
            }
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Authentication failed.']);
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
