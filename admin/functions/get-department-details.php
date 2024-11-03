<?php
// Include database connection
require_once '../../config.php';

// Process AJAX request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve department_id from POST data
    $departmentId = isset($_POST['department_id']) ? $_POST['department_id'] : null;

    if (!$departmentId) {
        // Return error response if department_id is not provided
        echo json_encode(['error' => 'Department ID is missing']);
        exit;
    }

    // Prepare SQL statement to fetch program details
    $sql = "SELECT * FROM tbl_departments WHERE department_id = :department_id";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT);

    try {
        // Execute the SQL statement
        if ($stmt->execute()) {
            // Fetch program details
            $program = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Return program details as JSON response
            echo json_encode($program);
            exit;
        } else {
            // Return error response if execution fails
            echo json_encode(['error' => 'Failed to fetch program details']);
            exit;
        }
    } catch (PDOException $e) {
        // Return error response if an exception occurs
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
} else {
    // Return error response if request method is not POST
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}
?>
