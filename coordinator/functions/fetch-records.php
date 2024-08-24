<?php
// Include the database connection file
require_once 'config.php';

// Check if the student_id is set in the session
if (isset($_SESSION['coordinator_id']) && isset($_SESSION['student_id'])) {
    // Prepare the SQL statement with JOIN to include data from tbl_users
    $sql = "
        SELECT 
            tl.*, 
            u.firstname, 
            u.lastname, 
            CONCAT(u.firstname, ' ', u.lastname) AS fullname
        FROM tbl_timelogs AS tl
        JOIN tbl_users AS u
        ON tl.student_id = u.student_id
        WHERE tl.student_id = :student_id
    ";

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
