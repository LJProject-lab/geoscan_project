<?php
// validate_user.php
header('Content-Type: application/json');

// Include the database configuration file
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $student_id = $data['student_id'];
    $email = $data['email'];

    try {
        // Check if student_id already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE student_id = ?");
        $stmt->execute([$student_id]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'Student ID already exists.']);
            exit;
        }

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'Email already exists.']);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Student ID and email are available.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database query error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
}
?>
