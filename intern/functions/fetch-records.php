<?php
// Include the database connection file
require_once '../config.php';

// Check if the student_id is set in the session
if (isset($_SESSION['student_id'])) {
    // Prepare the SQL statement
    $sql = "SELECT * FROM tbl_timelogs WHERE student_id = :student_id";

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        // Bind parameters
        $stmt->bindParam(':student_id', $_SESSION['student_id'], PDO::PARAM_INT);
        // Execute the query
        $stmt->execute();
        // Fetch all rows
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }
}
?>
