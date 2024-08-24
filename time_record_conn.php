<?php
require 'config.php';

// Initialize the message variable
$message = '';

// Retrieve form data
$student_id = $_POST['student_id'];
$pin = $_POST['pin'];
$type = $_POST['type'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$photo = $_POST['photo'];

// Validate that student_id exists in tbl_users
$stmt = $pdo->prepare("SELECT id, pin FROM tbl_users WHERE student_id = :student_id");
$stmt->execute(['student_id' => $student_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $message = "Error: Student ID does not exist.";
    echo $message;
    exit;
}

// Verify the provided pin against the stored hashed pin
if (!password_verify($pin, $user['pin'])) {
    $message = "Error: Invalid PIN.";
    echo $message;
    exit;
}

// Check if the photo was uploaded
if (!$photo) {
    $message = "Error: No photo uploaded.";
    echo $message;
    exit;
}

// Check for existing time-in or time-out for the student on the current day
$currentDate = date('Y-m-d');

// Query to check if the student has already timed in today
$checkTimeIn = $pdo->prepare("SELECT COUNT(*) FROM tbl_timelogs WHERE student_id = :student_id AND type = 'time_in' AND DATE(timestamp) = :date");
$checkTimeIn->execute(['student_id' => $student_id, 'date' => $currentDate]);
$hasTimeIn = $checkTimeIn->fetchColumn();

// Query to check if the student has already timed out today
$checkTimeOut = $pdo->prepare("SELECT COUNT(*) FROM tbl_timelogs WHERE student_id = :student_id AND type = 'time_out' AND DATE(timestamp) = :date");
$checkTimeOut->execute(['student_id' => $student_id, 'date' => $currentDate]);
$hasTimeOut = $checkTimeOut->fetchColumn();

// Validation based on the type
if ($type == 'time_in' && $hasTimeIn > 0) {
    $message = "Error: You have already timed in today.";
    echo $message;
    exit;
}

if ($type == 'time_out' && $hasTimeOut > 0) {
    $message = "Error: You have already timed out today.";
    echo $message;
    exit;
}

// Hash the PIN before storing it in tbl_timelogs
$pin_hash = password_hash($pin, PASSWORD_DEFAULT);

// Prepare the SQL statement to insert the data
$sql = "INSERT INTO tbl_timelogs (student_id, pin, type, timestamp, latitude, longitude, photo) 
        VALUES (:student_id, :pin, :type, NOW(), :latitude, :longitude, :photo)";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        'student_id' => $student_id,
        'pin' => $pin_hash, // Store the hashed PIN
        'type' => $type,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'photo' => $photo
    ]);

    // Display different success messages based on the type
    if ($type == 'time_in') {
        $message = "Time In successfully inserted!";
    } elseif ($type == 'time_out') {
        $message = "Time Out successfully inserted!";
    }

    echo $message;
} catch (PDOException $e) {
    $message = "Error: " . $e->getMessage();
    echo $message;
}
?>
