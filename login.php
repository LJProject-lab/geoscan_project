<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'config.php'; // Database connection

// Function to get the user's IP address
function getUserIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $student_id = $data['student_id'];
    $credential = $data['credential'];
    $longitude = $data['longitude'];
    $latitude = $data['latitude'];

    // Verify user and credentials (this part depends on your current verification logic)
    // Assuming verification is successful and user data is retrieved from the database
    $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE student_id = :student_id");
    $stmt->execute(['student_id' => $student_id]);
    $user = $stmt->fetch();

    if ($user) {
        // Check if the user has already timed in today
        $stmt = $pdo->prepare("SELECT * FROM tbl_timelogs WHERE student_id = :student_id AND DATE(time_in) = CURDATE()");
        $stmt->execute(['student_id' => $user['student_id']]);
        $existing_log = $stmt->fetch();

        if ($existing_log) {
            echo json_encode(['success' => false, 'message' => 'You have already timed in today.']);
        } else {
            // Insert log into the tbl_timelogs table
            $stmt = $pdo->prepare("INSERT INTO tbl_timelogs (student_id, email, longitude, latitude, time_in) VALUES (:student_id, :email, :longitude, :latitude, NOW())");
            $stmt->execute([
                'student_id' => $user['student_id'],
                'email' => $user['email'],
                'longitude' => $longitude,
                'latitude' => $latitude
            ]);

            // Set session variables
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['email'] = $user['email'];

            // Redirect to home.php
            echo json_encode(['success' => true, 'redirect' => 'home.php']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
