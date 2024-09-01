<?php
require 'config.php';

$response = ['status' => 'error', 'message' => '']; // Initialize response array

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
    $response['message'] = "Student ID does not exist.";
    echo json_encode($response);
    exit;
}

// Verify the provided pin against the stored hashed pin
if (!password_verify($pin, $user['pin'])) {
    $response['message'] = "Invalid PIN.";
    echo json_encode($response);
    exit;
}

// Check if the photo was uploaded
if (!$photo) {
    $response['message'] = "No photo uploaded.";
    echo json_encode($response);
    exit;
}

// Check if latitude and longitude are provided
if (empty($latitude) || empty($longitude)) {
    $response['message'] = "Location must be provided. Please enable your GPS location and try again.";
    echo json_encode($response);
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
    $response['message'] = "You have already recorded your Time In for today.";
    echo json_encode($response);
    exit;
}

if ($type == 'time_out') {
    if ($hasTimeIn <= 0) {
        $response['message'] = "You must record a Time In before you can Time Out.";
        echo json_encode($response);
        exit;
    }

    if ($hasTimeOut > 0) {
        $response['message'] = "You have already recorded your Time Out for today.";
        echo json_encode($response);
        exit;
    }
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

    // Update the response based on the type
    if ($type == 'time_in') {
        $response['status'] = 'success';
        $response['message'] = "Time In successfully recorded!";
    } elseif ($type == 'time_out') {
        $response['status'] = 'success';
        $response['message'] = "Time Out successfully recorded!";
    }

    echo json_encode($response);
} catch (PDOException $e) {
    $response['message'] = "Error: " . $e->getMessage();
    echo json_encode($response);
}
?>
