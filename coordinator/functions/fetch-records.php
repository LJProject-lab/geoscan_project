<?php
// Include the database connection file
require_once 'config.php';


// Initialize $logs as an empty array
$logs = [];

// Check if the coordinator_id is set in the session
if (isset($_SESSION['coordinator_id'])) {

    $coordinator_id = $_SESSION['coordinator_id'];

    // Prepare the SQL statement to fetch timelogs based on the coordinator's students
    $sql = "
        SELECT 
            tl.*, 
            u.firstname, 
            u.lastname, 
            CONCAT(u.firstname, ' ', u.lastname) AS fullname
        FROM tbl_timelogs AS tl
        JOIN tbl_users AS u
        ON tl.student_id = u.student_id
        WHERE u.coordinator_id = :coordinator_id
    ";

    try {
        // Prepare and execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':coordinator_id', $coordinator_id, PDO::PARAM_INT);
        $stmt->execute();
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle case when coordinator_id is not set
    echo "Coordinator ID is not set in the session.";
}
?>
