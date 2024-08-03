<?php
// register_fingerprint.php
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$student_id = $data['student_id'] ?? null;
$pin = $data['pin'] ?? null;
$firstname = $data['firstname'] ?? null;
$lastname = $data['lastname'] ?? null;
$email = $data['email'] ?? null;
$phone = $data['phone'] ?? null;
$address = $data['address'] ?? null;
$credential = $data['credential'] ?? null;

if (!$student_id || !$pin || !$firstname || !$lastname || !$email || !$phone || !$address || !$credential) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit;
}

$credential_id = $credential['id'] ?? null;
$attestation_object = $credential['response']['attestationObject'] ?? null;
$client_data_json = $credential['response']['clientDataJSON'] ?? null;

if (!$credential_id || !$attestation_object || !$client_data_json) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Incomplete credential data.']);
    exit;
}

try {
    // Check if the student_id already exists
    $check_sql = "SELECT COUNT(*) FROM tbl_users WHERE student_id = :student_id";
    $stmt = $pdo->prepare($check_sql);
    $stmt->execute([':student_id' => $student_id]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Student ID already exists.']);
        exit;
    }

    // Store the credential
    $sql = "INSERT INTO tbl_users (student_id, pin, firstname, lastname, email, phone, address, credential_id, attestation_object, client_data_json) VALUES (:student_id, :pin, :firstname, :lastname, :email, :phone, :address, :credential_id, :attestation_object, :client_data_json)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':student_id' => $student_id,
        ':pin' => $pin,
        ':firstname' => $firstname,
        ':lastname' => $lastname,
        ':email' => $email,
        ':phone' => $phone,
        ':address' => $address,
        ':credential_id' => $credential_id,
        ':attestation_object' => $attestation_object,
        ':client_data_json' => $client_data_json
    ]);

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
