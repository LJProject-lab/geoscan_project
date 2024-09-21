<?php
// Include database connection
require_once '../../config.php';

// Process AJAX request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve company_id from POST data
    $companyId = isset($_POST['company_id']) ? $_POST['company_id'] : null;

    if (!$companyId) {
        // Return error response if company_id is not provided
        echo json_encode(['error' => 'Company ID is missing']);
        exit;
    }

    // Prepare SQL statement to fetch company details
    $sql = "SELECT * FROM tbl_companies WHERE company_id = :company_id";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);

    try {
        // Execute the SQL statement
        if ($stmt->execute()) {
            // Fetch company details
            $company = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Return company details as JSON response
            echo json_encode($company);
            exit;
        } else {
            // Return error response if execution fails
            echo json_encode(['error' => 'Failed to fetch company details']);
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
