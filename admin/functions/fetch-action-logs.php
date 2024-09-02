<?php
// Include the database connection file
require_once '../config.php';

// Check if the admin_id is set in the session
if (isset($_SESSION['admin_id'])) {
    // Prepare the SQL statement
    $sql = "SELECT A.*, B.*, C.username, D.username FROM tbl_actionlogs AS A
            LEFT JOIN tbl_reference AS B ON A.action_id = B.action_id
            LEFT JOIN tbl_coordinators AS C ON C.coordinator_id = A.user_id
            LEFT JOIN tbl_admin AS D ON D.admin_id = A.user_id";

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        // Execute the query
        $stmt->execute();
        // Fetch all rows
        $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }
}
?>
