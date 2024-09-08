<?php

require_once '../config.php';
require 'phpspreadsheet/vendor/autoload.php';

if (!isset($_SESSION['import_data'])) {
    echo "No data to save.";
    exit();
}

$importData = $_SESSION['import_data'];
$programId = $_POST['program_id'];
$coordinatorId = $_POST['coordinator_id'];
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
$stmt = $pdo->prepare("INSERT INTO tbl_users (student_id, firstname, lastname, email, phone, address, program_id, coordinator_id) VALUES (:student_id, :firstname, :lastname, :email, :phone, :address, :program_id, :coordinator_id)");

foreach ($data as $row) {
    $stmt->execute([
        ':student_id' => $row[0],
        ':firstname' => $row[1],
        ':lastname' => $row[2],
        ':email' => $row[3],
        ':phone' => $row[4],
        ':address' => $row[5],
        ':program_id' => $programId,
        ':coordinator_id' => $coordinatorId
    ]);
}

// Unset session data
unset($_SESSION['import_data']);

// Redirect with success parameter
header('Location: add_intern.php?status=success');
exit();
?>
