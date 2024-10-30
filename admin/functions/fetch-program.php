<?php
// Include the database connection file
require_once '../config.php';

// Check if the admin_id is set in the session
if (isset($_SESSION['admin_id'])) {
    // Prepare the SQL statement
    $sql = "
    SELECT p.program_id, p.program_name, p.program_hour, p.createdAt, d.department_name
    FROM tbl_programs p
    JOIN tbl_departments d ON p.department_id = d.department_id
    ";

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        // Execute the query
        $stmt->execute();
        // Fetch all rows
        $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }
}
?>
