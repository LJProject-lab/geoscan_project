<?php
// Include database connection
require_once '../../config.php';

// Process AJAX request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve program_id from POST data
    $programId = isset($_POST['program_id']) ? $_POST['program_id'] : null;

    if (!$programId) {
        // Return error response if program_id is not provided
        echo json_encode(['error' => 'Program ID is missing']);
        exit;
    }

    // Prepare SQL statement to fetch program details
    $sql = "SELECT * FROM tbl_programs WHERE program_id = :program_id";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':program_id', $programId, PDO::PARAM_INT);

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
