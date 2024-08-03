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
        $location = $_POST['location'];
        $photo = $_POST['photo'];
        $timestamp = date('Y-m-d H:i:s');

        // Verify student credentials
        $sql = "SELECT * FROM students WHERE student_id = :student_id AND pin = :pin";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':pin', $pin);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            // Prepare an insert statement
            $sql = "INSERT INTO time_records (student_id, pin, type, timestamp, location, photo) VALUES (:student_id, :pin, :type, :timestamp, :location, :photo)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':pin', $pin);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':timestamp', $timestamp);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':photo', $photo, PDO::PARAM_LOB);

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
