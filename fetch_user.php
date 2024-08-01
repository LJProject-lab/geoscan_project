<?php
// fetch_user.php
header('Content-Type: application/json');

// Include the database configuration file
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$student_id = $data['student_id'] ?? '';

if (!$student_id) {
    echo json_encode(['success' => false, 'message' => 'Student ID is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT credentialId FROM tbl_users WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    $credentialId = base64_encode($user['credentialId']);
    $challenge = base64_encode(random_bytes(32));

    echo json_encode(['success' => true, 'credentialId' => $credentialId, 'challenge' => $challenge]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database query error: ' . $e->getMessage()]);
}
?>
