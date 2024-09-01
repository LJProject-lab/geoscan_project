<?php

header('Content-Type: application/json'); // Set the response content type to JSON


include_once '../includes/getAddress.php'; // Include the file with getAddress function

if (isset($_GET['latitude']) && isset($_GET['longitude'])) {
    $latitude = $_GET['latitude'];
    $longitude = $_GET['longitude'];

    try {
        $address = getAddress($latitude, $longitude);
        echo json_encode(['address' => $address]);
    } catch (Exception $e) {
        echo json_encode(['address' => 'Error fetching address']);
    }
} else {
    echo json_encode(['address' => 'Invalid coordinates']);
}

?>
