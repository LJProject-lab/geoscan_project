<?php

// Include database connection
require_once '../../config.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve form data
    $course_name = $_POST['course_name'];

    // Validate course name
    if (empty(trim($course_name))) {
        $msg = 'Please enter a Course Name.';
        // Return the error message
        echo json_encode(['msg' => $msg]);
        exit();
    } else {
        // Check if course name already exists
        $sql_check_course = 'SELECT course_name FROM tbl_courses WHERE course_name = :course_name';
        $stmt_check_course = $pdo->prepare($sql_check_course);
        $stmt_check_course->bindParam(':course_name', $course_name, PDO::PARAM_STR);
        $stmt_check_course->execute();
        if ($stmt_check_course->rowCount() > 0) {
            $msg = 'Course Name is already taken.';
            // Return the error message
            echo json_encode(['msg' => $msg]);
            exit();
        }
    }

    if (empty(trim($course_name))) {
        $msg = 'Please enter a Course Name.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Generate a unique course ID
    $course_id = mt_rand(10000, 99999);

    // Prepare and execute the first SQL statement
    $sql = 'INSERT INTO tbl_courses (course_id, course_name) VALUES (:course_id, :course_name)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':course_id', $course_id, PDO::PARAM_STR);
    $stmt->bindParam(':course_name', $course_name, PDO::PARAM_STR);
    
    if ($stmt->execute()) {       
        // Return success message
        $success = 'Course successfully created.';
        echo json_encode(['success' => $success]);
        exit();
    } else {
        // Return error message if something goes wrong
        $msg = 'Oops! Something went wrong. Please try again later.';
        echo json_encode(['msg' => $msg]);
        exit();
    }


}
?>
