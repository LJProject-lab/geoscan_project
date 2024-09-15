<?php

require_once '../config.php';
require 'phpspreadsheet/vendor/autoload.php';

// Include an encryption library or function. For simplicity, using PHP's built-in functions
// Make sure to define a proper encryption method or use a secure library
function encrypt($data, $key) {
    return openssl_encrypt($data, 'aes-256-cbc', $key, 0, $key);
}

// Secret key for encryption - should be securely stored and not hard-coded in a real application
$encryptionKey = 'your_secret_key_32chars_long'; // Replace with your actual secret key

if (!isset($_SESSION['import_data'])) {
    echo "No data to save.";
    exit();
}

$importData = $_SESSION['import_data'];
$programId = $_POST['program_id'];
$coordinatorId = $_POST['coordinator_id'];
$status = $_POST['status'];
$data = $importData['data'];


// Gather all student IDs from the data
$studentIds = array_column($data, 0);

// Prepare a statement to check if any student_id already exists
$placeholders = implode(',', array_fill(0, count($studentIds), '?'));
$checkStmt = $pdo->prepare("SELECT student_id FROM tbl_users WHERE student_id IN ($placeholders)");
$checkStmt->execute($studentIds);

$existingStudentIds = $checkStmt->fetchAll(PDO::FETCH_COLUMN);

if (!empty($existingStudentIds)) {
    // Duplicates found
    $duplicates = implode(',', $existingStudentIds);
    header('Location: review_import.php?status=error&duplicates=' . urlencode($duplicates));
    exit();
}

// No duplicates found, proceed with insertion
$stmt = $pdo->prepare("INSERT INTO tbl_users (student_id, firstname, lastname, email, phone, address, program_id, coordinator_id, pin, status) VALUES (:student_id, :firstname, :lastname, :email, :phone, :address, :program_id, :coordinator_id, :pin, :status)");

foreach ($data as $row) {
    // Extract last 4 digits of the student_id
    $studentId = $row[0];
    $pin = substr($studentId, -4); // Get the last 4 digits

    // Encrypt the PIN
    $encryptedPin = password_hash($pin, PASSWORD_DEFAULT);

    $stmt->execute([
        ':student_id' => $studentId,
        ':firstname' => $row[1],
        ':lastname' => $row[2],
        ':email' => $row[3],
        ':phone' => $row[4],
        ':address' => $row[5],
        ':program_id' => $programId,
        ':coordinator_id' => $coordinatorId,
        ':pin' => $encryptedPin,
        ':status' => $status
    ]);
}

// Unset session data
unset($_SESSION['import_data']);

// Redirect with success parameter
header('Location: add_intern.php?status=success');
exit();
?>
