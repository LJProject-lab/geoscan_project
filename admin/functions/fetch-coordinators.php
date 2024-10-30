<?php
// Include the database connection file
require_once '../config.php';

// Check if the admin_id is set in the session
if (isset($_SESSION['admin_id'])) {
    // Prepare the SQL statement
    $sql = "
    SELECT c.coordinator_id, c.username, c.firstname, c.lastname, c.email, c.createdAt, d.department_name
    FROM tbl_coordinators c
    JOIN tbl_departments d ON c.department_id = d.department_id
    ";

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        // Execute the query
        $stmt->execute();
        // Fetch all rows
        $coordinators = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }
}
?>
