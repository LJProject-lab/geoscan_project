<?php
// register.php
header('Content-Type: application/json');

// Include the database configuration file
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $student_id = $data['student_id'];
    $email = $data['email'];
    $credential = $data['credential'];

    $credentialId = base64_decode($credential['id']);
    $publicKey = base64_decode($credential['response']['attestationObject']);

    try {
        // Check if student_id already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE student_id = ?");
        $stmt->execute([$student_id]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['message' => 'Student ID already exists.']);
            exit;
        }

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['message' => 'Email already exists.']);
            exit;
        }

        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO tbl_users (student_id, email, displayName, credentialId, publicKey) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$student_id, $email, $student_id, $credentialId, $publicKey])) {
            echo json_encode(['message' => 'Fingerprint registered successfully.']);
        } else {
            echo json_encode(['message' => 'Failed to register fingerprint.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Database query error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['message' => 'Invalid data.']);
}
?>
