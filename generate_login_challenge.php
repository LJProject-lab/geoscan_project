<?php
// /mnt/data/generate_login_challenge.php

require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$student_id = $data['student_id'] ?? null;

if (!$student_id) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit;
}

// Fetch the credential ID from the database
$sql = "SELECT credential_id FROM users WHERE student_id = :student_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['student_id' => $student_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}

$credential_id = $user['credential_id'];

// Create a challenge for the login
$challenge = base64_encode(random_bytes(32));

header('Content-Type: application/json');
echo json_encode(['success' => true, 'challenge' => $challenge, 'credentialId' => $credential_id]);
?>
