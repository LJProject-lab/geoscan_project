<?php
// Include the database connection file
require_once '../config.php';

// Check if the admin_id is set in the session
if (isset($_SESSION['admin_id'])) {
    // Prepare the SQL statement
    $sql = "SELECT A.*, B.coordinator_id FROM tbl_adjustments AS A
    LEFT JOIN tbl_users AS B ON A.student_id = B.student_id";

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        // Execute the query
        $stmt->execute();
        // Fetch all rows
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }
}
?>
