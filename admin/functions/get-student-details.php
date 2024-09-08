<?php
// Include database connection
require_once '../../config.php';

// Process AJAX request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve student_id from POST data
    $studentId = isset($_POST['student_id']) ? $_POST['student_id'] : null;

    if (!$studentId) {
        // Return error response if student_id is not provided
        echo json_encode(['error' => 'Student ID is missing']);
        exit;
    }

    // Prepare SQL statement to fetch student details
    $sql = "SELECT * FROM tbl_users WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);

    try {
        // Execute the SQL statement
        if ($stmt->execute()) {
            // Fetch student details
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Return student details as JSON response
            echo json_encode($student);
            exit;
        } else {
            // Return error response if execution fails
            echo json_encode(['error' => 'Failed to fetch student details']);
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
