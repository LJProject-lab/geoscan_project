<?php
// Include database connection
require_once '../../config.php';

// Process delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve course_id from POST data
    $course_id = isset($_POST['course_id']) ? $_POST['course_id'] : null;

    if (!$course_id) {
        $msg = 'Course ID is missing.';
        echo json_encode(['msg' => $msg]);
        exit;
    }

    try {
        // Start a database transaction
        $pdo->beginTransaction();


        // Delete the course record from the database
        $sql_delete_course = "DELETE FROM tbl_courses WHERE course_id = :course_id";
        $stmt_delete_course = $pdo->prepare($sql_delete_course);
        $stmt_delete_course->bindParam(':course_id', $course_id, PDO::PARAM_INT);

        // Execute the SQL statement to delete the course
        if ($stmt_delete_course->execute()) {
            
            $pdo->commit();
            echo json_encode(['success' => 'Course deleted successfully']);
            exit;

        } else {
            // Rollback the transaction if deletion of the course fails
            $pdo->rollBack();
            echo json_encode(['msg' => 'Failed to delete course']);
            exit;
        }
    } catch (PDOException $e) {
        // Rollback the transaction and return error message if an exception occurs
        $pdo->rollBack();
        echo json_encode(['msg' => $e->getMessage()]);
        exit;
    }
} else {
    // Return error response if request method is not POST
    echo json_encode(['msg' => 'Invalid request method']);
    exit;
}
?>