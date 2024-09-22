<?php
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

$student_id = $data['student_id'] ?? '';
$pin = $data['pin'] ?? '';

if (empty($student_id) || empty($pin)) {
    echo json_encode(['success' => false, 'message' => 'Student ID and Pin are required.']);
    exit;
}

try {
    // Fetch student details and check if they are active
    $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE student_id = ? AND status = 'active'");
    $stmt->execute([$student_id]);
    $user = $stmt->fetch();

    if ($user) {
        // Compare plain text pin
        if (password_verify($pin, $user['pin'])) {
            // Set session variables if login is successful
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['phone'] = $user['phone'];
            $_SESSION['program_id'] = $user['program_id'];
            $_SESSION['address'] = $user['address'];

            echo json_encode(['success' => true]);
        } else {
            // If the pin is incorrect
            echo json_encode(['success' => false, 'message' => 'Invalid Pin.']);
        }
    } else {
        // Check if student exists but is inactive
        $inactiveStmt = $pdo->prepare("SELECT * FROM tbl_users WHERE student_id = ?");
        $inactiveStmt->execute([$student_id]);
        $inactiveUser = $inactiveStmt->fetch();

        if ($inactiveUser) {
            // If the student is found but inactive
            echo json_encode(['success' => false, 'message' => 'Your account is currently inactive. Kindly contact your coordinator for further assistance.']);
        } else {
            // If the student ID is not found
            echo json_encode(['success' => false, 'message' => 'Student ID not found.']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Login failed: ' . $e->getMessage()]);
}
?>
