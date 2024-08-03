<?php
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

$student_id = $data['student_id'] ?? '';
$pin = $data['pin'] ?? '';
$firstname = $data['firstname'] ?? '';
$lastname = $data['lastname'] ?? '';
$email = $data['email'] ?? '';
$phone = $data['phone'] ?? '';
$address = $data['address'] ?? '';

if (empty($student_id) || empty($pin) || empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($address)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

try {
    // Check for duplicate student_id
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE student_id = ?");
    $stmt->execute([$student_id]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Student ID already exists.']);
        exit;
    }

    // Check for duplicate email
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists.']);
        exit;
    }

    // Insert new user with plain text pin
    $stmt = $pdo->prepare("INSERT INTO tbl_users (student_id, pin, firstname, lastname, email, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$student_id, $pin, $firstname, $lastname, $email, $phone, $address]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()]);
}
?>
