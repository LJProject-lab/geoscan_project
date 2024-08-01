<?php
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$credential = $data['credential'] ?? null;

if (!$credential) {
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

// Validate the credential
try {
    $sql = "SELECT student_id FROM users WHERE credential_id = :credential_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':credential_id' => $credential_id]);
    $user = $stmt->fetch();

    if ($user) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'student_id' => $user['student_id']]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Authentication failed.']);
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
