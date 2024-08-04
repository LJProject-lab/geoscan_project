<?php
// Database configuration
$dsn = 'mysql:host=localhost;dbname=pnc';
$username = 'root'; // replace with your database username
$password = ''; // replace with your database password

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $student_id = $_POST['student_id'];
        $pin = $_POST['pin'];
        $type = $_POST['type'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $photo = $_POST['photo'];
        $timestamp = date('Y-m-d H:i:s');

        // Verify student credentials
        $sql = "SELECT * FROM tbl_users WHERE student_id = :student_id AND pin = :pin";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':pin', $pin);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {

            date_default_timezone_set('Asia/Manila');

            $current_datetime = date('Y-m-d H:i:s');

            // Prepare an insert statement
            $sql = "INSERT INTO tbl_timelogs (student_id, pin, type, timestamp, latitude, longitude, photo) VALUES (:student_id, :pin, :type, :timestamp, :latitude, :longitude, :photo)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':pin', $pin);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':timestamp', $current_datetime);
            $stmt->bindParam(':latitude', $latitude);
            $stmt->bindParam(':longitude', $longitude);
            $stmt->bindParam(':photo', $photo);

            // Execute the statement
            if ($stmt->execute()) {
                echo "Time record submitted successfully.";
            } else {
                echo "Error: Could not submit time record.";
            }
        } else {
            echo "Invalid student ID or PIN.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection
$pdo = null;
?>
